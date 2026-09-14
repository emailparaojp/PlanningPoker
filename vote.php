<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['session_id']) || !isset($_SESSION['user_name'])) {
    header('Location: index.php');
    exit();
}

$session_id = $_SESSION['session_id'];
$user_name = $_SESSION['user_name'];
$db = getDB();

$stmt = $db->prepare('SELECT * FROM sessions WHERE id = :id');
$stmt->bindValue(':id', $session_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$session = $result->fetchArray(SQLITE3_ASSOC);

if (!$session) {
    header('Location: index.php');
    exit();
}
$db->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Poker - Votação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 20px 0; }
        .navbar { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); }
        .navbar-brand { font-weight: 700; font-size: 22px; }
        .session-badge { background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 20px; color: white; font-weight: 500; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-header { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border-radius: 15px 15px 0 0; border: none; padding: 20px; }
        .card-header h5 { margin: 0; font-weight: 600; }
        .poker-card { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s; margin: 10px; flex: 1; min-height: 100px; display: flex; flex-direction: column; justify-content: center; border: 3px solid transparent; }
        .poker-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }
        .poker-card.selected { border-color: white; box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6); transform: scale(1.05); }
        .poker-card .card-value { font-size: 32px; font-weight: 700; line-height: 1; }
        .poker-card .card-description { font-size: 12px; margin-top: 8px; opacity: 0.9; }
        .voting-cards { display: flex; flex-wrap: wrap; gap: 0; }
        .participant-item { padding: 10px 15px; background: #f3f4f6; border-radius: 8px; margin-bottom: 8px; display: flex; align-items: center; }
        .participant-item i { color: #10b981; margin-right: 8px; }
        .vote-status { padding: 15px; background: #d1fae5; color: #065f46; border-radius: 10px; font-weight: 600; text-align: center; margin-top: 20px; }
        .current-story { background: #f3f4f6; padding: 20px; border-radius: 10px; }
        .current-story h3 { color: var(--primary); margin-bottom: 10px; }
        .current-story p { margin: 8px 0; }
        .results-hidden { color: #6b7280; font-style: italic; }
        .results-box { background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; border-radius: 8px; }
        .stat-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .stat-item:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-lg">
            <span class="navbar-brand"><i class="fas fa-chess"></i> Planning Poker</span>
            <div class="ms-auto">
                <span class="session-badge">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($user_name); ?>
                </span>
            </div>
        </div>
    </nav>

    <div class="container-lg py-4">
        <!-- Header Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-chess"></i>
                    <strong>Sessão:</strong> <?php echo htmlspecialchars($session['name']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Histórias -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> História Atual</h5>
                    </div>
                    <div class="card-body">
                        <div id="currentStory">
                            <div class="text-center text-muted">
                                <i class="fas fa-hourglass-half fa-2x mb-3"></i>
                                <p>Aguardando o Scrum Master apresentar uma história...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Votação -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-vote-yea"></i> Sua Votação</h5>
                    </div>
                    <div class="card-body">
                        <div id="votingCards" class="voting-cards"></div>
                        <p id="voteStatus"></p>
                    </div>
                </div>
            </div>

            <!-- Sideba -->
            <div class="col-lg-4">
                <!-- Participantes -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users"></i> Participantes</h5>
                    </div>
                    <div class="card-body">
                        <div id="participantsList"></div>
                    </div>
                </div>

                <!-- Resultados -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> Resultado</h5>
                    </div>
                    <div class="card-body">
                        <div id="votingResults">
                            <p class="results-hidden"><i class="fas fa-lock"></i> Os votos serão revelados pelo Scrum Master</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sessionId = <?php echo $session_id; ?>;
        const userName = "<?php echo htmlspecialchars($user_name); ?>";
        const isSM = false;
    </script>
    <script src="js/main.js"></script>
    <script src="js/vote.js"></script>
</body>
</html>
