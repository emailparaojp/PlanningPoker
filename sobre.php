<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Planning Poker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; }
        body { background: #f8f9fa; color: #333; }
        .navbar-custom { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); }
        .hero { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 60px 20px; text-align: center; }
        .hero h1 { font-size: 48px; font-weight: 700; margin-bottom: 10px; }
        .hero p { font-size: 18px; opacity: 0.95; }
        .section-title { color: var(--primary); font-weight: 700; margin: 40px 0 30px; text-align: center; font-size: 36px; border-bottom: 3px solid var(--primary); padding-bottom: 15px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border-radius: 15px 15px 0 0; border: none; }
        .scale-card { background: white; border: 2px solid #e0e0e0; border-radius: 12px; padding: 20px; text-align: center; transition: all 0.3s; }
        .scale-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2); }
        .scale-value { font-size: 40px; font-weight: 700; color: var(--primary); }
        .scale-title { font-weight: 600; margin: 10px 0 5px; }
        .scale-description { font-size: 14px; color: #666; line-height: 1.5; }
        .highlight-box { background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 4px solid var(--primary); padding: 20px; border-radius: 10px; margin: 20px 0; }
        .step-box { background: white; border-radius: 10px; padding: 20px; margin: 15px 0; border-left: 4px solid var(--primary); }
        .step-number { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 50%; font-weight: 700; margin-right: 15px; font-size: 18px; }
        .benefit-card { text-align: center; padding: 20px; background: white; border-radius: 10px; }
        .benefit-icon { font-size: 48px; color: var(--primary); margin-bottom: 10px; }
        .role-card { background: white; border-radius: 10px; padding: 25px; margin-bottom: 20px; border-left: 4px solid var(--primary); }
        .btn-custom { background: var(--primary); border: none; color: white; font-weight: 600; padding: 12px 30px; border-radius: 10px; }
        .btn-custom:hover { background: var(--secondary); color: white; }
        .back-link { background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .back-link a { color: var(--primary); text-decoration: none; font-weight: 600; font-size: 16px; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-lg">
            <span class="navbar-brand"><i class="fas fa-chess"></i> Planning Poker</span>
        </div>
    </nav>

    <div class="hero">
        <h1><i class="fas fa-book-open"></i> Sobre o Planning Poker</h1>
        <p>Conheça a técnica revolucionária para estimativas ágeis</p>
    </div>

    <div class="container-lg py-5">
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Voltar ao Início</a>
        </div>

        <!-- O que é Planning Poker -->
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="mb-0"><i class="fas fa-question-circle"></i> O que é Planning Poker?</h3>
            </div>
            <div class="card-body p-5">
                <p class="lead mb-4">
                    <strong>Planning Poker</strong> (também conhecido como Scrum Poker) é uma técnica gamificada de estimativa ágil baseada em consenso, amplamente utilizada por equipes Scrum para estimar o esforço de histórias de usuário.
                </p>

                <div class="highlight-box">
                    <h5><i class="fas fa-lightbulb"></i> Objetivo Principal</h5>
                    <p class="mb-0">Obter estimativas mais precisas através da sabedoria coletiva da equipe, evitando vieses de ancoragem e incentivando discussões produtivas.</p>
                </div>

                <h4 class="mt-4 mb-3">Por que usar Planning Poker?</h4>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="benefit-card">
                            <div class="benefit-icon"><i class="fas fa-bullseye"></i></div>
                            <h5>Precisão</h5>
                            <p class="small text-muted">Combina perspectivas da equipe</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="benefit-card">
                            <div class="benefit-icon"><i class="fas fa-comments"></i></div>
                            <h5>Discussões</h5>
                            <p class="small text-muted">Promove conversas ricas</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="benefit-card">
                            <div class="benefit-icon"><i class="fas fa-tachometer-alt"></i></div>
                            <h5>Rapidez</h5>
                            <p class="small text-muted">Estimativas eficientes</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="benefit-card">
                            <div class="benefit-icon"><i class="fas fa-handshake"></i></div>
                            <h5>Engajamento</h5>
                            <p class="small text-muted">Todos participam</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Escala de Pontuação -->
        <h2 class="section-title"><i class="fas fa-chart-bar"></i> Nossa Escala de Pontuação</h2>
        <p class="text-center mb-4">Utilizamos uma escala baseada em analogias do mundo real para facilitar o entendimento:</p>

        <div class="row g-3 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="scale-card">
                    <div class="scale-value">0.5</div>
                    <div class="scale-title">Apertar Parafuso</div>
                    <div class="scale-description">Tarefa trivial, extremamente simples. Pode ser feita em minutos.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="scale-card">
                    <div class="scale-value">1</div>
                    <div class="scale-title">Trocar Lâmpada</div>
                    <div class="scale-description">Tarefa pequena com esforço mínimo. Clara e direta.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="scale-card">
                    <div class="scale-value">3</div>
                    <div class="scale-title">Trocar Pisos</div>
                    <div class="scale-description">Mais trabalho, pouca complexidade. Requer algumas horas.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="scale-card">
                    <div class="scale-value">5</div>
                    <div class="scale-title">Construir Banheiro</div>
                    <div class="scale-description">Tarefa complexa com múltiplos componentes. Requer planejamento.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="scale-card">
                    <div class="scale-value">8</div>
                    <div class="scale-title">Construir Casa Pequena</div>
                    <div class="scale-description">Muito complexa com muitas dependências. Afeta múltiplos módulos.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="scale-card" style="border-color: #ef4444; border-left-color: #ef4444;">
                    <div class="scale-value" style="color: #ef4444;">13+</div>
                    <div class="scale-title">Precisa Quebrar!</div>
                    <div class="scale-description">Épico muito grande. Deve ser fatiado em histórias menores.</div>
                </div>
            </div>
        </div>

        <div class="highlight-box">
            <h5><i class="fas fa-exclamation-triangle"></i> Regra de Ouro</h5>
            <p class="mb-0">Se a história receber muitos votos acima de 8, é sinal de que ela precisa ser quebrada em histórias menores e mais gerenciáveis.</p>
        </div>

        <!-- Como Funciona -->
        <h2 class="section-title mt-5"><i class="fas fa-cogs"></i> Como Funciona?</h2>
        <div class="row">
            <div class="col-12">
                <div class="step-box">
                    <div style="display: flex; align-items: flex-start;">
                        <span class="step-number">1</span>
                        <div>
                            <h5>Scrum Master Cria Sessão</h5>
                            <p class="text-muted mb-0">O SM inicia uma nova sessão e recebe um código único para compartilhar com o time.</p>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; color: var(--primary); font-size: 24px; margin: 10px 0;"><i class="fas fa-arrow-down"></i></div>

                <div class="step-box">
                    <div style="display: flex; align-items: flex-start;">
                        <span class="step-number">2</span>
                        <div>
                            <h5>Time se Conecta</h5>
                            <p class="text-muted mb-0">Membros do time entram na sessão usando o código fornecido.</p>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; color: var(--primary); font-size: 24px; margin: 10px 0;"><i class="fas fa-arrow-down"></i></div>

                <div class="step-box">
                    <div style="display: flex; align-items: flex-start;">
                        <span class="step-number">3</span>
                        <div>
                            <h5>SM Apresenta História</h5>
                            <p class="text-muted mb-0">O Scrum Master cria uma história com título e descrição para ser estimada.</p>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; color: var(--primary); font-size: 24px; margin: 10px 0;"><i class="fas fa-arrow-down"></i></div>

                <div class="step-box">
                    <div style="display: flex; align-items: flex-start;">
                        <span class="step-number">4</span>
                        <div>
                            <h5>Time Vota Simultaneamente</h5>
                            <p class="text-muted mb-0">Cada membro escolhe secretamente um valor que representa sua compreensão da complexidade.</p>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; color: var(--primary); font-size: 24px; margin: 10px 0;"><i class="fas fa-arrow-down"></i></div>

                <div class="step-box">
                    <div style="display: flex; align-items: flex-start;">
                        <span class="step-number">5</span>
                        <div>
                            <h5>SM Revela os Votos</h5>
                            <p class="text-muted mb-0">Quando todos votarem, o SM revela os votos e a aplicação mostra média e mediana.</p>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; color: var(--primary); font-size: 24px; margin: 10px 0;"><i class="fas fa-arrow-down"></i></div>

                <div class="step-box">
                    <div style="display: flex; align-items: flex-start;">
                        <span class="step-number">6</span>
                        <div>
                            <h5>Discussão e Consenso</h5>
                            <p class="text-muted mb-0">A equipe discute votos discrepantes. Quem votou diferente explica suas razões.</p>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; color: var(--primary); font-size: 24px; margin: 10px 0;"><i class="fas fa-arrow-down"></i></div>

                <div class="step-box">
                    <div style="display: flex; align-items: flex-start;">
                        <span class="step-number">7</span>
                        <div>
                            <h5>Finalização</h5>
                            <p class="text-muted mb-0">Quando há consenso, o SM finaliza a história registrando a pontuação final acordada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Papéis -->
        <h2 class="section-title mt-5"><i class="fas fa-users"></i> Papéis na Aplicação</h2>
        <div class="row">
            <div class="col-lg-6">
                <div class="role-card">
                    <h4><i class="fas fa-user-tie"></i> Scrum Master</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Cria e gerencia a sessão</li>
                        <li><i class="fas fa-check text-success"></i> Cria histórias com título e descrição</li>
                        <li><i class="fas fa-check text-success"></i> Monitora quem já votou</li>
                        <li><i class="fas fa-check text-success"></i> Decide quando revelar os votos</li>
                        <li><i class="fas fa-check text-success"></i> Pode reiniciar votações</li>
                        <li><i class="fas fa-check text-success"></i> Finaliza histórias com pontuação</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="role-card">
                    <h4><i class="fas fa-users"></i> Time de Desenvolvimento</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Entra na sessão com código</li>
                        <li><i class="fas fa-check text-success"></i> Visualiza histórias do SM</li>
                        <li><i class="fas fa-check text-success"></i> Vota na complexidade</li>
                        <li><i class="fas fa-check text-success"></i> Pode alterar seu voto</li>
                        <li><i class="fas fa-check text-success"></i> Vê resultados após revelação</li>
                        <li><i class="fas fa-check text-success"></i> Participa das discussões</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recursos -->
        <h2 class="section-title mt-5"><i class="fas fa-star"></i> Recursos da Aplicação</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-lock text-primary"></i> Votação Oculta</h5>
                        <p class="card-text text-muted">Votos ficam secretos até revelação, evitando viés de ancoragem</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-sync text-primary"></i> Tempo Real</h5>
                        <p class="card-text text-muted">Interface atualiza automaticamente com novos votos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-line text-primary"></i> Estatísticas</h5>
                        <p class="card-text text-muted">Cálculo automático de média e mediana dos votos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users-alt text-primary"></i> Participantes</h5>
                        <p class="card-text text-muted">Veja quem está online na sessão em tempo real</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-history text-primary"></i> Histórico</h5>
                        <p class="card-text text-muted">Todas as histórias votadas ficam registradas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-mobile-alt text-primary"></i> Responsivo</h5>
                        <p class="card-text text-muted">Funciona perfeitamente em desktop, tablet e celular</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dicas -->
        <h2 class="section-title mt-5"><i class="fas fa-lightbulb"></i> Dicas para Boas Estimativas</h2>
        <div class="highlight-box">
            <ol>
                <li><strong>Estimem esforço, não tempo:</strong> Foque na complexidade relativa</li>
                <li><strong>Use histórias de referência:</strong> Compare com tarefas similares</li>
                <li><strong>Discuta discrepâncias:</strong> Votos diferentes revelam perspectivas diferentes</li>
                <li><strong>Mantenha histórias pequenas:</strong> Evite histórias maiores que 8 pontos</li>
                <li><strong>Vote honestamente:</strong> Não deixe votos anteriores influenciar o seu</li>
                <li><strong>Faça perguntas:</strong> Tire dúvidas antes de votar</li>
                <li><strong>Time completo:</strong> Inclua todos desenvolvedores, testadores e designers</li>
                <li><strong>Seja consistente:</strong> Use sempre a mesma escala e critérios</li>
            </ol>
        </div>

        <!-- CTA -->
        <div class="card mt-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border: none;">
            <div class="card-body text-center py-5">
                <h2 class="mb-3"><i class="fas fa-rocket"></i> Pronto para Começar?</h2>
                <p class="lead mb-4">Comece a estimar suas histórias de forma colaborativa e eficiente!</p>
                <a href="index.php" class="btn btn-light btn-lg">
                    <i class="fas fa-plus-circle"></i> Criar Planning Poker Agora
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
