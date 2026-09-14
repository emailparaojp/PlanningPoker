<?php
session_start();
if (isset($_SESSION['session_id']) && isset($_SESSION['user_name'])) {
    if (isset($_SESSION['is_sm']) && $_SESSION['is_sm']) {
        header('Location: sm.php');
    } else {
        header('Location: vote.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Poker - Estimativas Ágeis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; }
        body { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { background: rgba(0,0,0,0.1) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .navbar-brand { font-weight: 700; font-size: 24px; color: white !important; }
        .nav-link { color: white !important; font-weight: 500; }
        .nav-link:hover { text-shadow: 0 0 10px rgba(255,255,255,0.5); }
        .main-content { flex: 1; display: flex; align-items: center; padding: 40px 20px; }
        .hero-section { text-align: center; color: white; margin-bottom: 40px; }
        .hero-section h1 { font-size: 48px; font-weight: 700; margin-bottom: 20px; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .hero-section p { font-size: 20px; opacity: 0.95; }
        .card { border: none; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .btn-primary { background: var(--primary); border: none; padding: 12px 30px; font-weight: 600; font-size: 16px; }
        .btn-primary:hover { background: var(--secondary); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        .form-control { border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 15px; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .feature-icon { font-size: 40px; color: var(--primary); margin-bottom: 15px; }
        @media (max-width: 768px) { .hero-section h1 { font-size: 32px; } .hero-section p { font-size: 16px; } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-lg">
            <span class="navbar-brand"><i class="fas fa-chess"></i> Planning Poker</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto">
                    <a href="sobre.php" class="nav-link" target="_blank">
                        <i class="fas fa-info-circle"></i> Como Funciona
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container-lg w-100">
            <div class="hero-section">
                <h1><i class="fas fa-chart-pie"></i> Planning Poker</h1>
                <p>Estimativas ágeis colaborativas para equipes Scrum</p>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4" style="color: var(--primary);"><i class="fas fa-user-tie"></i> Scrum Master</h3>
                            <p class="text-muted mb-4">Crie uma nova sessão de Planning Poker e gerencie as votações do seu time.</p>
                            
                            <div class="mb-3">
                                <label class="form-label fw-600">Nome da Sessão</label>
                                <input type="text" class="form-control" id="sessionName" placeholder="Ex: Sprint 42">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-600">Seu Nome</label>
                                <input type="text" class="form-control" id="smName" placeholder="Ex: João Silva">
                            </div>
                            
                            <button onclick="createSession()" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle"></i> Criar Planning Poker
                            </button>
                            
                            <div class="alert alert-info mt-4 mb-0" role="alert">
                                <small><i class="fas fa-lightbulb"></i> <strong>Dica:</strong> Você receberá um código único para compartilhar com seu time.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4" style="color: var(--secondary);"><i class="fas fa-users"></i> Time</h3>
                            <p class="text-muted mb-4">Participe de uma sessão existente usando o código fornecido pelo Scrum Master.</p>
                            
                            <div class="mb-3">
                                <label class="form-label fw-600">Código da Sessão</label>
                                <input type="text" class="form-control" id="sessionCode" placeholder="Ex: ABC123" maxlength="6" style="text-transform: uppercase;">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-600">Seu Nome</label>
                                <input type="text" class="form-control" id="teamMemberName" placeholder="Ex: Maria Santos">
                            </div>
                            
                            <button onclick="joinSession()" class="btn btn-primary w-100">
                                <i class="fas fa-arrow-right-to-bracket"></i> Entrar na Sessão
                            </button>
                            
                            <div class="alert alert-info mt-4 mb-0" role="alert">
                                <small><i class="fas fa-lightbulb"></i> <strong>Dica:</strong> Solicite o código ao Scrum Master para participar.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 text-center text-white">
                <div class="col-md-3 col-sm-6">
                    <div class="feature-icon"><i class="fas fa-eye-slash"></i></div>
                    <h5>Votos Ocultos</h5>
                    <p class="small opacity-75">Evita viés de ancoragem</p>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="feature-icon"><i class="fas fa-sync-alt"></i></div>
                    <h5>Tempo Real</h5>
                    <p class="small opacity-75">Atualizações automáticas</p>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="feature-icon"><i class="fas fa-calculator"></i></div>
                    <h5>Estatísticas</h5>
                    <p class="small opacity-75">Média e mediana dos votos</p>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h5>Responsivo</h5>
                    <p class="small opacity-75">Funciona em qualquer dispositivo</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function createSession() {
            const sessionName = document.getElementById('sessionName').value.trim();
            const smName = document.getElementById('smName').value.trim();
            
            if (!sessionName || !smName) {
                alert('Por favor, preencha todos os campos');
                return;
            }
            
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);
            
            fetch('api/api.php?action=create_session', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({name: sessionName, sm_name: smName}),
                signal: controller.signal
            })
            .then(r => r.json())
            .then(data => {
                clearTimeout(timeoutId);
                if (data.success) window.location.href = 'sm.php';
                else alert(data.message || 'Erro ao criar sessão');
            })
            .catch(() => {
                clearTimeout(timeoutId);
                alert('Erro ao criar sessão');
            });
        }
        
        function joinSession() {
            const code = document.getElementById('sessionCode').value.trim().toUpperCase();
            const name = document.getElementById('teamMemberName').value.trim();
            
            if (!code || !name) {
                alert('Por favor, preencha todos os campos');
                return;
            }
            
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);
            
            fetch('api/api.php?action=join_session', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({code: code, name: name}),
                signal: controller.signal
            })
            .then(r => r.json())
            .then(data => {
                clearTimeout(timeoutId);
                if (data.success) window.location.href = 'vote.php';
                else alert(data.message || 'Erro ao entrar na sessão');
            })
            .catch(() => {
                clearTimeout(timeoutId);
                alert('Erro ao entrar na sessão');
            });
        }
        
        document.getElementById('smName')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') createSession();
        });
        document.getElementById('teamMemberName')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') joinSession();
        });
    </script>
</body>
</html>
