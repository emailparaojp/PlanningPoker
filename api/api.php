<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/db.php';

$action = $_GET['action'] ?? '';
$db = getDB();

switch ($action) {
    case 'create_session':
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $sm_name = $data['sm_name'] ?? '';
        
        if (empty($name) || empty($sm_name)) {
            echo json_encode(['success' => false, 'message' => 'Nome da sessão e Scrum Master são obrigatórios']);
            exit;
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
        
        // Adicionar SM como participante
        $stmt = $db->prepare('INSERT INTO participants (session_id, name) VALUES (:session_id, :name)');
        $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $sm_name, SQLITE3_TEXT);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'code' => $code, 'session_id' => $session_id]);
        break;
        
    case 'join_session':
        $data = json_decode(file_get_contents('php://input'), true);
        $code = strtoupper($data['code'] ?? '');
        $name = $data['name'] ?? '';
        
        if (empty($code) || empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Código da sessão e nome são obrigatórios']);
            exit;
        }
        
        $session = $db->querySingle("SELECT * FROM sessions WHERE code = '$code' AND active = 1", true);
        
        if (!$session) {
            echo json_encode(['success' => false, 'message' => 'Sessão não encontrada']);
            exit;
        }
        
        $_SESSION['session_id'] = $session['id'];
        $_SESSION['user_name'] = $name;
        $_SESSION['is_sm'] = false;
        
        // Adicionar participante
        $stmt = $db->prepare('INSERT INTO participants (session_id, name) VALUES (:session_id, :name)');
        $stmt->bindValue(':session_id', $session['id'], SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'session_id' => $session['id']]);
        break;
        
    case 'create_story':
        $data = json_decode(file_get_contents('php://input'), true);
        $session_id = $_SESSION['session_id'] ?? 0;
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        
        if (!$session_id || empty($title)) {
            echo json_encode(['success' => false, 'message' => 'Sessão inválida ou título vazio']);
            exit;
        }
        
        // Obter número da história
        $count = $db->querySingle("SELECT COUNT(*) FROM stories WHERE session_id = $session_id");
        $number = $count + 1;
        
        $stmt = $db->prepare('INSERT INTO stories (session_id, title, description, number) VALUES (:session_id, :title, :description, :number)');
        $stmt->bindValue(':session_id', $session_id, SQLITE3_INTEGER);
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'story_id' => $db->lastInsertRowID()]);
        break;
        
    case 'get_stories':
        $session_id = $_SESSION['session_id'] ?? 0;
        
        if (!$session_id) {
            echo json_encode(['success' => false, 'message' => 'Sessão inválida']);
            exit;
        }
        
        $results = $db->query("SELECT * FROM stories WHERE session_id = $session_id ORDER BY created_at DESC");
        $stories = [];
        
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            // Contar votos
            $vote_count = $db->querySingle("SELECT COUNT(*) FROM votes WHERE story_id = {$row['id']}");
            $row['vote_count'] = $vote_count;
            $stories[] = $row;
        }
        
        echo json_encode(['success' => true, 'stories' => $stories]);
        break;
        
    case 'vote':
        $data = json_decode(file_get_contents('php://input'), true);
        $story_id = $data['story_id'] ?? 0;
        $points = $data['points'] ?? 0;
        $voter_name = $_SESSION['user_name'] ?? '';
        
        if (!$story_id || !$voter_name) {
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            exit;
        }
        
        // Verificar se já votou e atualizar
        $existing = $db->querySingle("SELECT id FROM votes WHERE story_id = $story_id AND voter_name = '$voter_name'");
        
        if ($existing) {
            $stmt = $db->prepare('UPDATE votes SET points = :points, voted_at = CURRENT_TIMESTAMP WHERE id = :id');
            $stmt->bindValue(':points', $points, SQLITE3_FLOAT);
            $stmt->bindValue(':id', $existing, SQLITE3_INTEGER);
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
        $story_id = $_GET['story_id'] ?? 0;
        
        if (!$story_id) {
            echo json_encode(['success' => false, 'message' => 'ID da história inválido']);
            exit;
        }
        
        $results = $db->query("SELECT * FROM votes WHERE story_id = $story_id ORDER BY voted_at ASC");
        $votes = [];
        
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $votes[] = $row;
        }
        
        echo json_encode(['success' => true, 'votes' => $votes]);
        break;
        
    case 'reset_votes':
        $story_id = $_POST['story_id'] ?? 0;
        
        if (!$story_id) {
            echo json_encode(['success' => false, 'message' => 'ID da história inválido']);
            exit;
        }
        
        $db->exec("DELETE FROM votes WHERE story_id = $story_id");
        echo json_encode(['success' => true]);
        break;
        
    case 'finalize_story':
        $data = json_decode(file_get_contents('php://input'), true);
        $story_id = $data['story_id'] ?? 0;
        $final_points = $data['final_points'] ?? 0;
        
        if (!$story_id) {
            echo json_encode(['success' => false, 'message' => 'ID da história inválido']);
            exit;
        }
        
        $stmt = $db->prepare('UPDATE stories SET status = "completed", final_points = :final_points WHERE id = :id');
        $stmt->bindValue(':final_points', $final_points, SQLITE3_FLOAT);
        $stmt->bindValue(':id', $story_id, SQLITE3_INTEGER);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
        break;
        
    case 'get_participants':
        $session_id = $_SESSION['session_id'] ?? 0;
        
        if (!$session_id) {
            echo json_encode(['success' => false, 'message' => 'Sessão inválida']);
            exit;
        }
        
        $results = $db->query("SELECT DISTINCT name FROM participants WHERE session_id = $session_id ORDER BY last_activity DESC");
        $participants = [];
        
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $participants[] = $row['name'];
        }
        
        echo json_encode(['success' => true, 'participants' => $participants]);
        break;
        
    case 'update_activity':
        $session_id = $_SESSION['session_id'] ?? 0;
        $user_name = $_SESSION['user_name'] ?? '';
        
        if ($session_id && $user_name) {
            $db->exec("UPDATE participants SET last_activity = CURRENT_TIMESTAMP WHERE session_id = $session_id AND name = '$user_name'");
        }
        
        echo json_encode(['success' => true]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
}
?>
