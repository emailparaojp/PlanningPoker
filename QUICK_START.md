# Guia Rápido - Planning Poker

## 🚀 Início Rápido

### Opção 1: PHP Built-in Server (Mais Simples)

1. Abra o terminal/prompt de comando
2. Navegue até a pasta do projeto:
```cmd
cd c:\a\planningPoker
```

3. Inicie o servidor:
```cmd
php -S localhost:8000
```

4. Abra o navegador em: **http://localhost:8000**

### Opção 2: XAMPP/WAMP

1. Copie a pasta `planningPoker` para:
   - XAMPP: `C:\xampp\htdocs\`
   - WAMP: `C:\wamp\www\`

2. Inicie Apache no painel de controle

3. Abra o navegador em: **http://localhost/planningPoker**

---

## 📝 Passo a Passo de Uso

### 1️⃣ Scrum Master: Criar Sessão

1. Acesse a página inicial
2. Preencha:
   - Nome da Sessão: "Sprint 10"
   - Seu Nome: "João Silva"
3. Clique em **"Criar Planning Poker"**
4. Anote o **código da sessão** (exemplo: A1B2C3)
5. Compartilhe com o time

### 2️⃣ Time: Entrar na Sessão

1. Acesse a página inicial
2. Preencha:
   - Código da Sessão: A1B2C3
   - Seu Nome: "Maria Santos"
3. Clique em **"Entrar na Sessão"**

### 3️⃣ Scrum Master: Criar História

1. No painel SM, preencha:
   - Título: "Implementar tela de login"
   - Descrição: "Como usuário, quero fazer login..."
2. Clique em **"Criar História"**

### 4️⃣ Time: Votar

1. Veja a história apresentada
2. Clique no card com a pontuação desejada
3. Aguarde o SM revelar os votos

### 5️⃣ Scrum Master: Gerenciar Votação

1. Clique em **"Gerenciar Votação"** na história
2. Acompanhe quantos votaram (sem ver os valores)
3. Clique em **"Revelar Votos"**
4. Veja estatísticas (média e mediana)
5. Discuta com o time
6. Clique em **"Finalizar História"** e digite pontuação final

---

## ❓ Resolução de Problemas

### Erro: "Call to undefined function sqlite_open"
✅ Solução: Habilite a extensão SQLite no php.ini:
```ini
extension=sqlite3
```

### Erro 404 ao acessar
✅ Verifique:
- Servidor está rodando?
- Porta 8000 está livre?
- Caminho está correto?

### Sessão não encontrada
✅ Verifique:
- Código digitado corretamente (case-insensitive)
- Sessão foi criada com sucesso

### Votos não aparecem
✅ Aguarde alguns segundos (atualização automática a cada 3s)

---

## 🎯 Dicas

✨ **Para o Scrum Master:**
- Crie várias histórias antes de iniciar
- Revele votos apenas quando todos tiverem votado
- Use as estatísticas para facilitar consenso
- Reinicie votação se necessário

✨ **Para o Time:**
- Você pode mudar seu voto antes da revelação
- Preste atenção às descrições de cada pontuação
- Se >8, sugira "fatiar" a história

---

## 📞 Suporte

Dúvidas? Verifique o README.md completo!
