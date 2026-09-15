<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once '../includes/db.php';

$db = getDB();

// Validar sessão para ações que precisam
function requireSession() {
    global $db;

    if (!isset($_SESSION['session_id']) || !isset($_SESSION['user_name'])) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'message' => 'Sessão inválida']));
    }

    $stmt = $db->prepare('SELECT active FROM sessions WHERE id = :session_id');
    $stmt->bindValue(':session_id', $_SESSION['session_id'], SQLITE3_INTEGER);
    $result = $stmt->execute();
    $session = $result->fetchArray(SQLITE3_ASSOC);

    if (!$session || (int)$session['active'] !== 1) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'message' => 'Sessão encerrada']));
    }
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create_session':
            $data = json_decode(file_get_contents('php://input'), true);
            $name = trim($data['name'] ?? '');
            $sm_name = trim($data['sm_name'] ?? '');
            
            if (strlen($name) < 3 || strlen($sm_name) < 2) {
                throw new Exception('Nome inválido');
            }
            
            $code = generateSessionCode();
            $stmt = $db->prepare('INSERT INTO sessions (code, name, sm_name) VALUES (:code, :name, :sm_name)');
            $stmt->bindValue(':code', $code, SQLITE3_TEXT);
            $stmt->bindValue(':name', $name, SQLITE3_TEXT);
            $stmt->bindValue(':sm_name', $sm_name, SQLITE3_TEXT);
            $stmt->execute();
            
            $session_id = $db->lastInsertRowID();
            $_SESSION['session_id'] = $session_id;
            $_SESSION['user_name'] = $sm_name;
            $_SESSION['is_sm'] = true;
            
            $stmt = $db->prepare('INSERT INTO participants (session_id, name) VALUES (:session_id, :name)');
            $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
            $stmt->bindValue(':name', $sm_name, SQLITE3_TEXT);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'code' => $code, 'session_id' => $session_id]);
            break;
            
        case 'join_session':
            $data = json_decode(file_get_contents('php://input'), true);
            $code = strtoupper(trim($data['code'] ?? ''));
            $name = trim($data['name'] ?? '');
            
            if (strlen($code) !== 6 || strlen($name) < 2) {
                throw new Exception('Dados inválidos');
            }
            
            $stmt = $db->prepare('SELECT * FROM sessions WHERE code = :code AND active = 1');
            $stmt->bindValue(':code', $code, SQLITE3_TEXT);
            $result = $stmt->execute();
            $session = $result->fetchArray(SQLITE3_ASSOC);
            
            if (!$session) {
                throw new Exception('Sessão não encontrada');
            }
            
            $_SESSION['session_id'] = $session['id'];
            $_SESSION['user_name'] = $name;
            $_SESSION['is_sm'] = false;
            
            $stmt = $db->prepare('INSERT INTO participants (session_id, name) VALUES (:session_id, :name)');
            $stmt->bindValue(':session_id', $session['id'], SQLITE3_INTEGER);
            $stmt->bindValue(':name', $name, SQLITE3_TEXT);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'session_id' => $session['id']]);
            break;

        case 'leave_session':
            requireSession();
            $session_id = $_SESSION['session_id'];
            $user_name = $_SESSION['user_name'];
            $is_sm = !empty($_SESSION['is_sm']);

            if ($is_sm) {
                $stmt = $db->prepare('UPDATE sessions SET active = 0 WHERE id = :session_id');
                $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
                $stmt->execute();

                $stmt = $db->prepare('DELETE FROM participants WHERE session_id = :session_id');
                $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
                $stmt->execute();
            }

            $stmt = $db->prepare('DELETE FROM votes WHERE voter_name = :name AND story_id IN (SELECT id FROM stories WHERE session_id = :session_id)');
            $stmt->bindValue(':name', $user_name, SQLITE3_TEXT);
            $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
            $stmt->execute();

            $stmt = $db->prepare('DELETE FROM participants WHERE session_id = :session_id AND name = :name');
            $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
            $stmt->bindValue(':name', $user_name, SQLITE3_TEXT);
            $stmt->execute();

            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();

            echo json_encode(['success' => true]);
            break;
            
        case 'create_story':
            requireSession();
            $data = json_decode(file_get_contents('php://input'), true);
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');
            
            if (strlen($title) < 3) {
                throw new Exception('Título inválido');
            }
            
            $session_id = $_SESSION['session_id'];
            $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM stories WHERE session_id = :sid");
            $stmt->bindValue(':sid', $session_id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);
            $count = $row['cnt'] ?? 0;
            
            $stmt = $db->prepare('INSERT INTO stories (session_id, title, description, number) VALUES (:session_id, :title, :description, :number)');
            $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
            $stmt->bindValue(':title', $title, SQLITE3_TEXT);
            $stmt->bindValue(':description', $description ?: '', SQLITE3_TEXT);
            $stmt->bindValue(':number', $count + 1, SQLITE3_INTEGER);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'story_id' => $db->lastInsertRowID()]);
            break;
            
        case 'get_stories':
            requireSession();
            $session_id = $_SESSION['session_id'];
            
            $query = "SELECT s.*, COALESCE(COUNT(v.id), 0) as vote_count
                      FROM stories s
                      LEFT JOIN votes v ON s.id = v.story_id
                      WHERE s.session_id = :sid
                      GROUP BY s.id
                      ORDER BY s.created_at DESC";
            
            $stmt = $db->prepare($query);
            $stmt->bindValue(':sid', $session_id, SQLITE3_INTEGER);
            $results = $stmt->execute();
            $stories = [];
            
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $stories[] = $row;
            }
            
            echo json_encode(['success' => true, 'stories' => $stories]);
            break;
            
        case 'vote':
            requireSession();
            $data = json_decode(file_get_contents('php://input'), true);
            $story_id = (int)($data['story_id'] ?? 0);
            $points = (float)($data['points'] ?? 0);
            
            if ($story_id <= 0 || $points < 0) {
                throw new Exception('Dados inválidos');
            }
            
            $voter_name = $_SESSION['user_name'];
            
            $stmt = $db->prepare('SELECT id FROM votes WHERE story_id = :sid AND voter_name = :vname');
            $stmt->bindValue(':sid', $story_id, SQLITE3_INTEGER);
            $stmt->bindValue(':vname', $voter_name, SQLITE3_TEXT);
            $result = $stmt->execute();
            $existing = $result->fetchArray(SQLITE3_ASSOC);
            
            if ($existing) {
                $stmt = $db->prepare('UPDATE votes SET points = :points, voted_at = CURRENT_TIMESTAMP WHERE id = :id');
                $stmt->bindValue(':points', $points, SQLITE3_FLOAT);
                $stmt->bindValue(':id', $existing['id'], SQLITE3_INTEGER);
            } else {
                $stmt = $db->prepare('INSERT INTO votes (story_id, voter_name, points) VALUES (:story_id, :voter_name, :points)');
                $stmt->bindValue(':story_id', $story_id, SQLITE3_INTEGER);
                $stmt->bindValue(':voter_name', $voter_name, SQLITE3_TEXT);
                $stmt->bindValue(':points', $points, SQLITE3_FLOAT);
            }
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;
            
        case 'get_votes':
            $story_id = (int)($_GET['story_id'] ?? 0);
            if ($story_id <= 0) {
                throw new Exception('ID inválido');
            }
            
            $stmt = $db->prepare('SELECT voter_name, points FROM votes WHERE story_id = :sid ORDER BY voted_at ASC');
            $stmt->bindValue(':sid', $story_id, SQLITE3_INTEGER);
            $results = $stmt->execute();
            $votes = [];
            
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $votes[] = $row;
            }
            
            echo json_encode(['success' => true, 'votes' => $votes]);
            break;
            
        case 'reset_votes':
            requireSession();
            $story_id = (int)($_POST['story_id'] ?? 0);
            if ($story_id <= 0) {
                throw new Exception('ID inválido');
            }
            
            $stmt = $db->prepare('DELETE FROM votes WHERE story_id = :sid');
            $stmt->bindValue(':sid', $story_id, SQLITE3_INTEGER);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;
            
        case 'finalize_story':
            requireSession();
            $data = json_decode(file_get_contents('php://input'), true);
            $story_id = (int)($data['story_id'] ?? 0);
            $final_points = (float)($data['final_points'] ?? 0);
            
            if ($story_id <= 0 || $final_points < 0) {
                throw new Exception('Dados inválidos');
            }
            
            $stmt = $db->prepare('UPDATE stories SET status = "completed", final_points = :points WHERE id = :id');
            $stmt->bindValue(':points', $final_points, SQLITE3_FLOAT);
            $stmt->bindValue(':id', $story_id, SQLITE3_INTEGER);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;
            
        case 'get_participants':
            requireSession();
            $session_id = $_SESSION['session_id'];
            
            $stmt = $db->prepare('SELECT DISTINCT name FROM participants WHERE session_id = :sid ORDER BY last_activity DESC');
            $stmt->bindValue(':sid', $session_id, SQLITE3_INTEGER);
            $results = $stmt->execute();
            $participants = [];
            
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $participants[] = $row['name'];
            }
            
            echo json_encode(['success' => true, 'participants' => $participants]);
            break;
            
        case 'update_activity':
            requireSession();
            $session_id = $_SESSION['session_id'];
            $user_name = $_SESSION['user_name'];
            
            $stmt = $db->prepare('UPDATE participants SET last_activity = CURRENT_TIMESTAMP WHERE session_id = :sid AND name = :name');
            $stmt->bindValue(':sid', $session_id, SQLITE3_INTEGER);
            $stmt->bindValue(':name', $user_name, SQLITE3_TEXT);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if ($db) $db->close();
}
?>
