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
$session = $db->querySingle("SELECT * FROM sessions WHERE id = $session_id", true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Poker - Votação</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Planning Poker</h1>
            <div class="session-info">
                <p><strong>Sessão:</strong> <?php echo htmlspecialchars($session['name']); ?></p>
                <p><strong>Bem-vindo:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                <p><a href="sobre.php" target="_blank" style="color: #667eea; text-decoration: none; font-weight: 600;">📚 Como Funciona?</a></p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-left">
                <div class="card">
                    <h2>História Atual</h2>
                    <div id="currentStory" class="current-story">
                        <p class="waiting-message">Aguardando o Scrum Master iniciar uma votação...</p>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Sua Votação</h2>
                    <div id="votingCards" class="voting-cards">
                        <div class="poker-card" onclick="vote(0.5)">
                            <div class="card-value">0.5</div>
                            <div class="card-description">Apertar parafuso</div>
                        </div>
                        <div class="poker-card" onclick="vote(1)">
                            <div class="card-value">1</div>
                            <div class="card-description">Trocar lâmpada</div>
                        </div>
                        <div class="poker-card" onclick="vote(3)">
                            <div class="card-value">3</div>
                            <div class="card-description">Trocar pisos</div>
                        </div>
                        <div class="poker-card" onclick="vote(5)">
                            <div class="card-value">5</div>
                            <div class="card-description">Construir banheiro</div>
                        </div>
                        <div class="poker-card" onclick="vote(8)">
                            <div class="card-value">8</div>
                            <div class="card-description">Construir casa</div>
                        </div>
                        <div class="poker-card poker-card-break" onclick="vote(13)">
                            <div class="card-value">13+</div>
                            <div class="card-description">Precisa quebrar</div>
                        </div>
                    </div>
                    <p id="voteStatus" class="vote-status"></p>
                </div>
            </div>
            
            <div class="col-right">
                <div class="card">
                    <h2>Participantes</h2>
                    <div id="participantsList" class="participants-list"></div>
                </div>
                
                <div class="card">
                    <h2>Resultado da Votação</h2>
                    <div id="votingResults" class="voting-results">
                        <p class="waiting-message">Os votos serão revelados pelo Scrum Master</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const sessionId = <?php echo $session_id; ?>;
        const userName = "<?php echo htmlspecialchars($user_name); ?>";
        const isSM = false;
    </script>
    <script src="js/main.js"></script>
    <script src="js/vote.js"></script>
</body>
</html>
