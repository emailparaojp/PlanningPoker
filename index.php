<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Poker - Início</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1 style="margin: 0;">Planning Poker</h1>
            <a href="sobre.php" style="text-decoration: none; color: #667eea; font-weight: 600; padding: 10px 20px; border: 2px solid #667eea; border-radius: 5px; transition: all 0.3s;" onmouseover="this.style.background='#667eea'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#667eea';">📚 Como Funciona?</a>
        </div>
        <div class="card">
            <h2>Bem-vindo!</h2>
            <p>Crie uma nova sessão de Planning Poker para começar a estimar suas histórias.</p>
            
            <div class="form-group">
                <label for="sessionName">Nome da Sessão:</label>
                <input type="text" id="sessionName" placeholder="Ex: Sprint 10 - Planejamento">
            </div>
            
            <div class="form-group">
                <label for="smName">Seu Nome (Scrum Master):</label>
                <input type="text" id="smName" placeholder="Digite seu nome">
            </div>
            
            <button onclick="createSession()" class="btn btn-primary">Criar Planning Poker</button>
            
            <hr>
            
            <h3>Ou entre em uma sessão existente:</h3>
            <div class="form-group">
                <label for="sessionCode">Código da Sessão:</label>
                <input type="text" id="sessionCode" placeholder="Digite o código">
            </div>
            
            <div class="form-group">
                <label for="teamMemberName">Seu Nome:</label>
                <input type="text" id="teamMemberName" placeholder="Digite seu nome">
            </div>
            
            <button onclick="joinSession()" class="btn btn-secondary">Entrar na Sessão</button>
        </div>
        
        <div class="info-card">
            <h3>Escala de Pontuação:</h3>
            <ul>
                <li><strong>0,5</strong> - Apertar parafuso (tarefa trivial)</li>
                <li><strong>1</strong> - Trocar uma lâmpada (tarefa pequena com esforço mínimo)</li>
                <li><strong>3</strong> - Trocar alguns pisos (mais trabalho com pouca complexidade)</li>
                <li><strong>5</strong> - Construir um banheiro (tarefa complexa com múltiplos componentes)</li>
                <li><strong>8</strong> - Construir uma casa pequena (tarefa muito complexa com muitas dependências)</li>
                <li><strong>Mais de 8</strong> - Precisamos quebrar (fatiar)</li>
            </ul>
        </div>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
