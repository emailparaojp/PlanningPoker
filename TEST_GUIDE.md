# Planning Poker - Guia de Testes

## Teste Rápido da Aplicação

### Servidor já está rodando em http://localhost:8000

## Fluxo de Teste Recomendado:

### 1. Página Inicial
- Abra http://localhost:8000/index.php
- Verifique que o Design Bootstrap está carregando corretamente
- Verifique que o formulário de criação de sessão aparece

### 2. Criar Sessão (Scrum Master)
- Preencha "Nome da Sessão": `Sprint Test 1`
- Preencha "Seu Nome": `João SM`
- Clique em "Criar Planning Poker"
- Você deve ser redirecionado para sm.php
- Copie o código de sessão (ex: ABC123)

### 3. Entrar na Sessão (Time)
- Abra http://localhost:8000/ em outra aba/janela
- Preencha "Código da Sessão": `[código copiado]`
- Preencha "Seu Nome": `Maria Dev`
- Clique em "Entrar na Sessão"
- Você deve ser redirecionado para vote.php

### 4. Criar História (SM)
- Na aba do SM (sm.php)
- Preencha "Título": `Implementar Login`
- Preencha "Descrição": `Autenticação de usuários`
- Clique em "Criar História"
- A história deve aparecer na lista

### 5. Votar (Time)
- Na aba do Time (vote.php)
- A história "Implementar Login" deve aparecer
- Clique em um dos cards de votação (ex: 5 pontos)
- Deve aparecer "✓ Seu voto: 5 pontos"

### 6. Revelar Votos (SM)
- Na aba do SM
- Clique em "Gerenciar" na história
- Verá os votos ocultos (✓)
- Clique em "Revelar Votos"
- Os votos devem aparecer com valores e estatísticas

### 7. Finalizar História (SM)
- Clique em "Finalizar"
- Digite a pontuação final: `5`
- A história deve ser marcada como concluída

## Características Testadas

✓ Bootstrap 5 CDN carregando corretamente
✓ Responsividade em diferentes tamanhos de tela
✓ Criação de sessões com código único
✓ Join de sessão existente
✓ Criação de histórias
✓ Votação em tempo real
✓ Reveal de votos com estatísticas
✓ Finalização de histórias
✓ Atualização automática de dados
✓ Lista de participantes online

## Arquivos Modificados/Criados

- index.php ✓ (Refatorado com Bootstrap 5)
- sm.php ✓ (Refatorado com Bootstrap 5)
- vote.php ✓ (Refatorado com Bootstrap 5)
- api/api.php ✓ (Melhorado com validações)
- includes/db.php ✓ (Otimizado)
- js/sm.js ✓ (Reescrito para Bootstrap modal)
- js/vote.js ✓ (Melhorado)
- js/main.js ✓ (Optimizado)

## Próximas Etapas (Opcional)

1. Refatorar sobre.php com Bootstrap 5
2. Melhorar CSS/estilos personalizados
3. Adicionar mais funcionalidades (ex: undo, export)
4. Deploy em produção

## Comando para Iniciar o Servidor

```bash
cd c:\Users\joao.goncalves\Downloads\public_html
php -S localhost:8000
```

Acesse: http://localhost:8000/