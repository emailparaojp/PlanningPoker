# Instalação do PHP no Windows

## 📥 Método 1: PHP Standalone (Recomendado para Testes)

### Passo a Passo:

1. **Download do PHP:**
   - Acesse: https://windows.php.net/download/
   - Baixe: "VS16 x64 Thread Safe" (versão mais recente)
   - Exemplo: `php-8.2.x-Win32-vs16-x64.zip`

2. **Extrair:**
   - Extraia para: `C:\php`

3. **Configurar php.ini:**
   ```cmd
   cd C:\php
   copy php.ini-development php.ini
   ```

4. **Editar php.ini:**
   - Abra `C:\php\php.ini` no Notepad
   - Encontre e descomente (remover `;`):
     ```ini
     extension=sqlite3
     extension=pdo_sqlite
     extension=mbstring
     extension=openssl
     ```

5. **Adicionar ao PATH:**
   - Clique com botão direito em "Este Computador" → Propriedades
   - Configurações avançadas do sistema
   - Variáveis de Ambiente
   - Em "Variáveis do Sistema", selecione "Path" → Editar
   - Clique em "Novo" → Digite: `C:\php`
   - OK → OK → OK

6. **Verificar:**
   - Abra novo CMD (importante: novo!)
   - Digite: `php -v`
   - Deve mostrar a versão do PHP

---

## 📦 Método 2: XAMPP (Mais Completo)

### Passo a Passo:

1. **Download:**
   - Acesse: https://www.apachefriends.org/
   - Baixe XAMPP para Windows

2. **Instalar:**
   - Execute o instalador
   - Aceite as configurações padrão
   - Instale em: `C:\xampp`

3. **Configurar:**
   - Abra o XAMPP Control Panel
   - Clique em "Start" no Apache
   - Clique em "Start" no MySQL (opcional)

4. **Usar Planning Poker:**
   - Copie a pasta `planningPoker` para `C:\xampp\htdocs\`
   - Acesse: http://localhost/planningPoker

---

## 🔧 Verificar Instalação

### Teste Rápido:

1. Abra CMD/PowerShell
2. Digite:
   ```cmd
   php -v
   ```
   Deve mostrar: `PHP 8.x.x`

3. Teste SQLite:
   ```cmd
   php -m | findstr sqlite
   ```
   Deve mostrar: `sqlite3` e `pdo_sqlite`

### Teste Completo:

1. Navegue até a pasta do projeto:
   ```cmd
   cd c:\a\planningPoker
   ```

2. Execute o teste de configuração:
   ```cmd
   php test_config.php
   ```
   ou acesse pelo navegador após iniciar o servidor:
   ```cmd
   php -S localhost:8000
   ```
   Depois abra: http://localhost:8000/test_config.php

---

## ❓ Problemas Comuns

### "php não é reconhecido..."
✅ PHP não está no PATH. Refaça o passo 5 do Método 1.

### "Call to undefined function sqlite_open"
✅ SQLite não habilitado. Edite php.ini e descomente `extension=sqlite3`

### Porta 8000 já em uso
✅ Use outra porta:
```cmd
php -S localhost:8080
```

### Apache não inicia no XAMPP
✅ Porta 80 pode estar ocupada. Mude para 8080 no httpd.conf ou pare o Skype/IIS.

---

## 🚀 Iniciar Projeto Após Instalação

### Usando PHP Built-in:
```cmd
cd c:\a\planningPoker
start_server.bat
```

### Usando XAMPP:
1. Inicie Apache no XAMPP Control Panel
2. Acesse: http://localhost/planningPoker

---

## 💡 Dicas

- Use PHP 8.0+ para melhor performance
- Mantenha extensões SQLite3 sempre habilitadas
- Para produção, use Apache ou Nginx ao invés do servidor built-in
- Faça backup do arquivo php.ini após configurar

---

## 📞 Links Úteis

- PHP Downloads: https://windows.php.net/download/
- XAMPP: https://www.apachefriends.org/
- Documentação PHP: https://www.php.net/docs.php
- PHP no Windows: https://www.php.net/manual/pt_BR/install.windows.php
