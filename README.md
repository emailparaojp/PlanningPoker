# Planning Poker

Uma aplicação web completa de Planning Poker para estimativas ágeis de equipes Scrum.

## 🎯 Funcionalidades

- **Criar Sessões de Planning Poker**: O Scrum Master pode criar novas sessões com código único
- **Gerenciamento de Histórias**: Criar histórias com título e descrição
- **Votação em Tempo Real**: Membros do time votam nas histórias usando a escala Fibonacci adaptada
- **Sistema de Revelação**: Votos ficam ocultos até o Scrum Master revelar
- **Estatísticas**: Cálculo automático de média e mediana dos votos
- **Participantes Online**: Lista de participantes ativos na sessão

## 📊 Escala de Pontuação

- **0.5** - Apertar parafuso (tarefa trivial)
- **1** - Trocar uma lâmpada (tarefa pequena com esforço mínimo)
- **3** - Trocar alguns pisos (mais trabalho envolvido com pouca complexidade)
- **5** - Construir um banheiro (tarefa complexa com múltiplos componentes)
- **8** - Construir uma casa pequena (tarefa muito complexa com muitas dependências)
- **13+** - Precisamos quebrar (fatiar a história)

## 🛠️ Tecnologias Utilizadas

- **HTML5** - Estrutura das páginas
- **CSS3** - Estilização e responsividade
- **JavaScript** - Interatividade e comunicação com API
- **PHP** - Backend e API REST
- **SQLite** - Banco de dados

## 📁 Estrutura do Projeto

```
planningPoker/
├── index.php           # Página inicial
├── sm.php             # Página do Scrum Master
├── vote.php           # Página de votação do time
├── sobre.php          # Página explicativa (Como funciona)
├── api/
│   └── api.php        # API REST (endpoints)
├── includes/
│   └── db.php         # Conexão e inicialização do banco
├── css/
│   └── style.css      # Estilos da aplicação
├── js/
│   ├── main.js        # Funções compartilhadas
│   ├── sm.js          # Funções do Scrum Master
│   └── vote.js        # Funções de votação
└── database/
    └── poker.db       # Banco SQLite (criado automaticamente)
```

## 🚀 Como Executar

### Pré-requisitos

- PHP 7.4 ou superior
- Extensão SQLite3 habilitada no PHP
- Servidor web (Apache, Nginx) ou PHP built-in server

### Instalação

1. Clone ou baixe este repositório:
```bash
cd c:\a\planningPoker
```

2. Inicie o servidor PHP:
```bash
php -S localhost:8000
```

3. Acesse no navegador:
```
http://localhost:8000
```

### Usando com XAMPP/WAMP

1. Copie a pasta `planningPoker` para o diretório `htdocs` (XAMPP) ou `www` (WAMP)
2. Acesse: `http://localhost/planningPoker`

## 📖 Como Usar

### Para o Scrum Master:

1. Acesse a página inicial
2. Preencha o nome da sessão e seu nome
3. Clique em "Criar Planning Poker"
4. Compartilhe o código da sessão com o time
5. Crie histórias usando o formulário
6. Gerencie votações:
   - Clique em "Gerenciar Votação" em uma história
   - Acompanhe os votos (ocultos)
   - Clique em "Revelar Votos" para mostrar os resultados
   - Veja estatísticas (média e mediana)
   - Finalize a história com a pontuação consenso

### Para o Time:

1. Acesse a página inicial
2. Digite o código da sessão fornecido pelo SM
3. Digite seu nome
4. Clique em "Entrar na Sessão"
5. Aguarde o SM apresentar uma história
6. Vote clicando em um dos cards de pontuação
7. Você pode alterar seu voto antes da revelação

## 🔄 Funcionalidades em Tempo Real

A aplicação atualiza automaticamente:
- Lista de participantes online (a cada 5 segundos)
- História atual para votação (a cada 3 segundos)
- Contagem de votos (a cada 3 segundos)
- Status da atividade dos usuários (a cada 10 segundos)

## 🗄️ Estrutura do Banco de Dados

### Tabela: sessions
- id, code, name, sm_name, created_at, active

### Tabela: stories
- id, session_id, title, description, number, status, final_points, created_at

### Tabela: votes
- id, story_id, voter_name, points, voted_at

### Tabela: participants
- id, session_id, name, last_activity

## 🎨 Personalização

Você pode personalizar cores e estilos editando o arquivo `css/style.css`:
- Cores principais: `#667eea` (roxo) e `#764ba2` (roxo escuro)
- Gradiente de fundo pode ser alterado em `body`

## 📱 Responsividade

A aplicação é totalmente responsiva e funciona bem em:
- Desktop
- Tablets
- Smartphones

## 🔒 Segurança

- Uso de prepared statements para prevenir SQL Injection
- htmlspecialchars para prevenir XSS
- Sessões PHP para autenticação básica

## 🐛 Problemas Conhecidos

- Não há autenticação robusta (adequado para uso em rede local/confiável)
- Sessões não expiram automaticamente
- Não há sincronização em tempo real via WebSocket (usa polling)

## 📝 Melhorias Futuras

- Implementar WebSocket para atualizações em tempo real
- Adicionar autenticação com senha
- Exportar resultados em CSV/PDF
- Histórico de sessões anteriores
- Timer para votações
- Modo observador (sem direito a voto)

## 📄 Licença

Este projeto é livre para uso pessoal e comercial.

## 👨‍💻 Autor

Desenvolvido como ferramenta de Planning Poker para equipes ágeis.

---

**Pronto para usar!** 🚀
