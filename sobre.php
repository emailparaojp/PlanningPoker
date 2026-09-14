<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Planning Poker</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .sobre-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .nav-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-bar h1 {
            margin: 0;
            color: #667eea;
            font-size: 24px;
        }
        
        .nav-link {
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
            padding: 10px 20px;
            border: 2px solid #667eea;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            background: #667eea;
            color: white;
        }
        
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .content-section h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 28px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .content-section h3 {
            color: #764ba2;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .content-section p {
            line-height: 1.8;
            color: #555;
            margin-bottom: 15px;
        }
        
        .content-section ul, .content-section ol {
            line-height: 1.8;
            color: #555;
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        .content-section li {
            margin-bottom: 10px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .scale-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .scale-item {
            background: #f9fafb;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s;
        }
        
        .scale-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.2);
        }
        
        .scale-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .scale-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .scale-description {
            font-size: 14px;
            color: #666;
        }
        
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .benefit-item {
            text-align: center;
            padding: 20px;
        }
        
        .benefit-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        .benefit-title {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .flow-diagram {
            background: white;
            padding: 20px;
            border: 2px dashed #667eea;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .flow-step {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        
        .flow-arrow {
            text-align: center;
            color: #667eea;
            font-size: 24px;
            margin: 10px 0;
        }
        
        @media (max-width: 768px) {
            .nav-bar {
                flex-direction: column;
                text-align: center;
            }
            
            .nav-link {
                margin-top: 10px;
            }
            
            .scale-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container sobre-container">
        <div class="nav-bar">
            <h1>📚 Sobre o Planning Poker</h1>
            <a href="index.php" class="nav-link">← Voltar ao Início</a>
        </div>
        
        <div class="content-section">
            <h2>O que é Planning Poker?</h2>
            <p>
                <strong>Planning Poker</strong> (também conhecido como Scrum Poker) é uma técnica gamificada de estimativa ágil 
                baseada em consenso, amplamente utilizada por equipes Scrum e ágeis para estimar o esforço ou tamanho relativo 
                de histórias de usuário, tarefas ou itens do backlog.
            </p>
            
            <div class="highlight-box">
                <p><strong>💡 Objetivo:</strong> Obter estimativas mais precisas através da sabedoria coletiva da equipe, 
                evitando vieses de ancoragem e incentivando discussões produtivas sobre o trabalho a ser realizado.</p>
            </div>
            
            <h3>Por que usar Planning Poker?</h3>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">🎯</div>
                    <div class="benefit-title">Estimativas Precisas</div>
                    <p>Combina perspectivas de toda a equipe</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">💬</div>
                    <div class="benefit-title">Discussões Ricas</div>
                    <p>Promove conversas sobre complexidade</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">⚡</div>
                    <div class="benefit-title">Rápido</div>
                    <p>Estimativas eficientes em grupo</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🤝</div>
                    <div class="benefit-icon">Engajamento</div>
                    <p>Todos participam ativamente</p>
                </div>
            </div>
        </div>
        
        <div class="content-section">
            <h2>Nossa Escala de Pontuação</h2>
            <p>
                Utilizamos uma escala adaptada baseada em analogias do mundo real para facilitar o entendimento 
                e a estimativa. Cada pontuação representa um nível de complexidade e esforço:
            </p>
            
            <div class="scale-grid">
                <div class="scale-item">
                    <div class="scale-value">0.5</div>
                    <div class="scale-title">Apertar Parafuso</div>
                    <div class="scale-description">
                        Tarefa trivial, extremamente simples. Pode ser feita em minutos sem necessidade de planejamento.
                        Exemplo: Corrigir um texto, ajustar uma cor.
                    </div>
                </div>
                
                <div class="scale-item">
                    <div class="scale-value">1</div>
                    <div class="scale-title">Trocar Lâmpada</div>
                    <div class="scale-description">
                        Tarefa pequena com esforço mínimo. Clara e direta, sem complexidade técnica significativa.
                        Exemplo: Adicionar um campo em formulário, atualizar texto.
                    </div>
                </div>
                
                <div class="scale-item">
                    <div class="scale-value">3</div>
                    <div class="scale-title">Trocar Alguns Pisos</div>
                    <div class="scale-description">
                        Mais trabalho envolvido, mas com pouca complexidade. Requer algumas horas de desenvolvimento.
                        Exemplo: Criar uma nova tela simples, implementar validação básica.
                    </div>
                </div>
                
                <div class="scale-item">
                    <div class="scale-value">5</div>
                    <div class="scale-title">Construir Banheiro</div>
                    <div class="scale-description">
                        Tarefa complexa com múltiplos componentes. Envolve diferentes partes do sistema e requer planejamento.
                        Exemplo: Implementar autenticação, criar API completa.
                    </div>
                </div>
                
                <div class="scale-item">
                    <div class="scale-value">8</div>
                    <div class="scale-title">Construir Casa Pequena</div>
                    <div class="scale-description">
                        Tarefa muito complexa com muitas dependências. Afeta múltiplos módulos e requer coordenação.
                        Exemplo: Reestruturar banco de dados, migração de sistema.
                    </div>
                </div>
                
                <div class="scale-item" style="border-color: #ef4444;">
                    <div class="scale-value" style="color: #ef4444;">13+</div>
                    <div class="scale-title">Precisa Quebrar!</div>
                    <div class="scale-description">
                        Épico muito grande. Deve ser fatiado em histórias menores antes de ser estimado e desenvolvido.
                        Indica necessidade de decomposição da tarefa.
                    </div>
                </div>
            </div>
            
            <div class="highlight-box">
                <p><strong>⚠️ Regra de Ouro:</strong> Se a história receber muitos votos acima de 8, é sinal de que 
                ela precisa ser quebrada em histórias menores e mais gerenciáveis.</p>
            </div>
        </div>
        
        <div class="content-section">
            <h2>Como Funciona?</h2>
            
            <div class="flow-diagram">
                <div class="flow-step">
                    <span class="step-number">1</span>
                    <div>
                        <strong>Scrum Master Cria Sessão</strong><br>
                        O SM inicia uma nova sessão de Planning Poker e recebe um código único para compartilhar.
                    </div>
                </div>
                
                <div class="flow-arrow">↓</div>
                
                <div class="flow-step">
                    <span class="step-number">2</span>
                    <div>
                        <strong>Time se Conecta</strong><br>
                        Membros do time entram na sessão usando o código fornecido e seus nomes.
                    </div>
                </div>
                
                <div class="flow-arrow">↓</div>
                
                <div class="flow-step">
                    <span class="step-number">3</span>
                    <div>
                        <strong>SM Apresenta História</strong><br>
                        O Scrum Master cria uma história (user story) com título e descrição para ser estimada.
                    </div>
                </div>
                
                <div class="flow-arrow">↓</div>
                
                <div class="flow-step">
                    <span class="step-number">4</span>
                    <div>
                        <strong>Time Vota Simultaneamente</strong><br>
                        Cada membro escolhe secretamente um valor da escala que representa seu entendimento da complexidade.
                        Os votos ficam ocultos até serem revelados.
                    </div>
                </div>
                
                <div class="flow-arrow">↓</div>
                
                <div class="flow-step">
                    <span class="step-number">5</span>
                    <div>
                        <strong>SM Revela os Votos</strong><br>
                        Quando todos votarem, o SM revela os votos. A aplicação mostra cada voto e calcula 
                        automaticamente a média e mediana.
                    </div>
                </div>
                
                <div class="flow-arrow">↓</div>
                
                <div class="flow-step">
                    <span class="step-number">6</span>
                    <div>
                        <strong>Discussão e Consenso</strong><br>
                        Se houver discrepância (votos muito diferentes), a equipe discute. Quem votou mais alto 
                        e mais baixo explica suas razões. Isso revela aspectos importantes da tarefa.
                    </div>
                </div>
                
                <div class="flow-arrow">↓</div>
                
                <div class="flow-step">
                    <span class="step-number">7</span>
                    <div>
                        <strong>Re-votação (se necessário)</strong><br>
                        Após a discussão, pode-se fazer uma nova rodada de votação ou o SM pode reiniciar a votação.
                    </div>
                </div>
                
                <div class="flow-arrow">↓</div>
                
                <div class="flow-step">
                    <span class="step-number">8</span>
                    <div>
                        <strong>Finalização</strong><br>
                        Quando houver consenso, o SM finaliza a história registrando a pontuação final acordada.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-section">
            <h2>Papéis na Aplicação</h2>
            
            <h3>👨‍💼 Scrum Master (SM)</h3>
            <ul>
                <li>Cria e gerencia a sessão de Planning Poker</li>
                <li>Cria histórias com título e descrição</li>
                <li>Monitora quem já votou (sem ver os votos)</li>
                <li>Decide quando revelar os votos</li>
                <li>Pode reiniciar votações se necessário</li>
                <li>Finaliza histórias com pontuação consensuada</li>
                <li>Acompanha participantes online</li>
            </ul>
            
            <h3>👥 Time de Desenvolvimento</h3>
            <ul>
                <li>Entra na sessão usando código fornecido</li>
                <li>Visualiza história atual apresentada pelo SM</li>
                <li>Vota na complexidade/esforço da história</li>
                <li>Pode alterar seu voto antes da revelação</li>
                <li>Vê resultados após revelação pelo SM</li>
                <li>Participa das discussões para consenso</li>
            </ul>
        </div>
        
        <div class="content-section">
            <h2>Recursos da Aplicação</h2>
            
            <h3>✨ Funcionalidades Principais</h3>
            <ul>
                <li><strong>Votação Oculta:</strong> Votos ficam secretos até revelação, evitando viés de ancoragem</li>
                <li><strong>Atualização em Tempo Real:</strong> Interface atualiza automaticamente mostrando novos votos e participantes</li>
                <li><strong>Estatísticas Automáticas:</strong> Cálculo de média e mediana dos votos</li>
                <li><strong>Lista de Participantes:</strong> Veja quem está online na sessão</li>
                <li><strong>Histórico de Histórias:</strong> Todas as histórias votadas ficam registradas</li>
                <li><strong>Múltiplas Sessões:</strong> Várias equipes podem usar simultaneamente com códigos únicos</li>
                <li><strong>Sem Cadastro:</strong> Não requer criação de contas, apenas nome e código</li>
                <li><strong>Design Responsivo:</strong> Funciona perfeitamente em desktop, tablet e celular</li>
            </ul>
            
            <h3>🔒 Privacidade e Segurança</h3>
            <ul>
                <li>Dados armazenados localmente no servidor</li>
                <li>Banco de dados SQLite isolado</li>
                <li>Sessões PHP para autenticação básica</li>
                <li>Proteção contra SQL Injection e XSS</li>
            </ul>
        </div>
        
        <div class="content-section">
            <h2>Dicas para Boas Estimativas</h2>
            
            <div class="highlight-box">
                <h3 style="margin-top: 0;">📌 Melhores Práticas</h3>
                <ol>
                    <li><strong>Estimem esforço, não tempo:</strong> Foque na complexidade relativa, não em horas/dias</li>
                    <li><strong>Use histórias de referência:</strong> Compare com tarefas similares já realizadas</li>
                    <li><strong>Discuta discrepâncias:</strong> Votos muito diferentes revelam entendimentos distintos</li>
                    <li><strong>Mantenha histórias pequenas:</strong> Evite histórias maiores que 8 pontos</li>
                    <li><strong>Vote honestamente:</strong> Não deixe que votos anteriores influenciem o seu</li>
                    <li><strong>Faça perguntas:</strong> Tire dúvidas antes de votar</li>
                    <li><strong>Time completo:</strong> Inclua todos desenvolvedores, testadores e designers</li>
                    <li><strong>Seja consistente:</strong> Use sempre a mesma escala e critérios</li>
                </ol>
            </div>
        </div>
        
        <div class="content-section">
            <h2>Tecnologias Utilizadas</h2>
            <p>Esta aplicação foi desenvolvida com tecnologias web simples e eficientes:</p>
            <ul>
                <li><strong>HTML5:</strong> Estrutura semântica das páginas</li>
                <li><strong>CSS3:</strong> Estilização moderna e responsiva</li>
                <li><strong>JavaScript (Vanilla):</strong> Interatividade e comunicação com API</li>
                <li><strong>PHP:</strong> Backend e API REST</li>
                <li><strong>SQLite:</strong> Banco de dados leve e portátil</li>
            </ul>
            <p>
                A aplicação não requer dependências externas, frameworks pesados ou instalação de pacotes. 
                Basta ter PHP com SQLite habilitado e você está pronto para usar!
            </p>
        </div>
        
        <div class="content-section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h2 style="color: white; border-bottom-color: white;">Pronto para Começar?</h2>
            <p style="color: white; font-size: 18px;">
                Comece a estimar suas histórias de forma colaborativa e eficiente!
            </p>
            <a href="index.php" class="btn btn-primary" style="background: white; color: #667eea; margin-top: 20px; display: inline-block;">
                Criar Planning Poker Agora
            </a>
        </div>
    </div>
</body>
</html>
