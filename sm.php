<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['session_id']) || !isset($_SESSION['is_sm'])) {
    header('Location: index.php');
    exit();
}

$session_id = $_SESSION['session_id'];
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
    <title>Planning Poker - Scrum Master</title>
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
        .btn-primary { background: var(--primary); border: none; }
        .btn-primary:hover { background: var(--secondary); }
        .form-control { border-radius: 10px; border: 2px solid #e0e0e0; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .story-item { background: white; border-left: 4px solid var(--primary); padding: 15px; border-radius: 8px; margin-bottom: 12px; transition: all 0.3s; cursor: pointer; }
        .story-item:hover { transform: translateX(5px); box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
        .story-item.completed { border-left-color: #10b981; background: #f0fdf4; }
        .story-badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-completed { background: #d1fae5; color: #065f46; }
        .participant-item { padding: 10px 15px; background: #f3f4f6; border-radius: 8px; margin-bottom: 8px; display: flex; align-items: center; }
        .participant-item i { color: #10b981; margin-right: 8px; }
        .vote-card { background: var(--primary); color: white; padding: 15px; border-radius: 10px; text-align: center; margin: 8px 0; font-weight: 600; }
        .vote-card.hidden { background: #e5e7eb; color: #6b7280; }
        .modal-content { border-radius: 15px; }
        .modal-header { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border-radius: 15px 15px 0 0; border: none; }
        .modal-header .btn-close { filter: invert(1); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-lg">
            <span class="navbar-brand"><i class="fas fa-chess"></i> Planning Poker</span>
            <div class="ms-auto">
                <span class="session-badge">
                    <i class="fas fa-code"></i> <?php echo htmlspecialchars($session['code']); ?>
                </span>
                <button type="button" class="btn btn-outline-light btn-sm ms-2" onclick="leaveSession()">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </button>
            </div>
        </div>
    </nav>

    <div class="container-lg py-4">
        <!-- Header Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Sessão:</strong> <?php echo htmlspecialchars($session['name']); ?> | 
                    <strong>Código para compartilhar:</strong> <code><?php echo htmlspecialchars($session['code']); ?></code>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Criar História -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-plus-circle"></i> Criar Nova História</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-600">Título</label>
                            <input type="text" class="form-control" id="storyTitle" placeholder="Ex: Implementar autenticação">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-600">Descrição (opcional)</label>
                            <textarea class="form-control" id="storyDescription" rows="3" placeholder="Detalhes adicionais..."></textarea>
                        </div>
                        
                        <button onclick="createStory()" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Criar História
                        </button>
                    </div>
                </div>

                <!-- Participantes -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users"></i> Participantes Online</h5>
                    </div>
                    <div class="card-body">
                        <div id="participantsList"></div>
                    </div>
                </div>
            </div>

            <!-- Histórias -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Histórias</h5>
                    </div>
                    <div class="card-body">
                        <div id="storiesList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Votação -->
    <div class="modal fade" id="votingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStoryTitle"></h5>
                    <button type="button" class="btn-close" onclick="closeVotingModal()"></button>
                </div>
                <div class="modal-body">
                    <p id="modalStoryDescription" class="text-muted"></p>
                    
                    <h6 class="mt-4 mb-3"><i class="fas fa-chart-bar"></i> Votos Recebidos:</h6>
                    <div id="votesDisplay"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="resetVoting()">
                        <i class="fas fa-redo"></i> Reiniciar
                    </button>
                    <button type="button" class="btn btn-primary" id="revealBtn" onclick="revealVotes()">
                        <i class="fas fa-eye"></i> Revelar Votos
                    </button>
                    <button type="button" class="btn btn-success" onclick="finalizeStory()">
                        <i class="fas fa-check"></i> Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sessionId = <?php echo $session_id; ?>;
        const isSM = true;
    </script>
    <script src="js/main.js"></script>
    <script src="js/sm.js"></script>
</body>
</html>
