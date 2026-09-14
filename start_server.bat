@echo off
echo ========================================
echo   Planning Poker - Iniciando Servidor
echo ========================================
echo.
echo Verificando PHP...

where php >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERRO] PHP nao encontrado no PATH!
    echo.
    echo Instale o PHP ou adicione ao PATH do Windows.
    echo.
    pause
    exit /b 1
)

php --version
echo.
echo ========================================
echo   Servidor iniciado em:
echo   http://localhost:8000
echo ========================================
echo.
echo Pressione Ctrl+C para parar o servidor
echo.

cd /d "%~dp0"
php -S localhost:8000

pause
