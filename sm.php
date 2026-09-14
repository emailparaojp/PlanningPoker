<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['session_id']) || !isset($_SESSION['is_sm'])) {
    header('Location: index.php');
    exit();
}

$session_id = $_SESSION['session_id'];
$db = getDB();
$session = $db->querySingle("SELECT * FROM sessions WHERE id = $session_id", true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Poker - Scrum Master</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Planning Poker - Scrum Master</h1>
            <div class="session-info">
                <p><strong>Sessão:</strong> <?php echo htmlspecialchars($session['name']); ?></p>
                <p><strong>Código:</strong> <span class="session-code"><?php echo htmlspecialchars($session['code']); ?></span></p>
                <p><a href="sobre.php" target="_blank" style="color: #667eea; text-decoration: none; font-weight: 600;">📚 Como Funciona?</a></p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-left">
                <div class="card">
                    <h2>Criar Nova História</h2>
                    <div class="form-group">
                        <label for="storyTitle">Título da História:</label>
                        <input type="text" id="storyTitle" placeholder="Ex: Implementar login do usuário">
                    </div>
                    
                    <div class="form-group">
                        <label for="storyDescription">Descrição (opcional):</label>
                        <textarea id="storyDescription" rows="3" placeholder="Detalhes da história..."></textarea>
                    </div>
                    
                    <button onclick="createStory()" class="btn btn-primary">Criar História</button>
                </div>
                
                <div class="card">
                    <h2>Participantes Online</h2>
                    <div id="participantsList" class="participants-list"></div>
                </div>
            </div>
            
            <div class="col-right">
                <div class="card">
                    <h2>Histórias para Votar</h2>
                    <div id="storiesList" class="stories-list"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="votingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeVotingModal()">&times;</span>
            <h2 id="modalStoryTitle"></h2>
            <p id="modalStoryDescription"></p>
            
            <h3>Votos Recebidos:</h3>
            <div id="votesDisplay" class="votes-display"></div>
            
            <div class="voting-actions">
                <button onclick="revealVotes()" class="btn btn-primary" id="revealBtn">Revelar Votos</button>
                <button onclick="resetVoting()" class="btn btn-secondary">Reiniciar Votação</button>
                <button onclick="finalizeStory()" class="btn btn-success">Finalizar História</button>
            </div>
        </div>
    </div>
    
    <script>
        const sessionId = <?php echo $session_id; ?>;
        const isSM = true;
    </script>
    <script src="js/main.js"></script>
    <script src="js/sm.js"></script>
</body>
</html>
