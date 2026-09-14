<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste de Configuração - Planning Poker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .test-item {
            background: #f5f5f5;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #ccc;
        }
        .test-item.ok { border-left-color: green; }
        .test-item.fail { border-left-color: red; }
        h1 { color: #667eea; }
    </style>
</head>
<body>
    <h1>🔧 Teste de Configuração - Planning Poker</h1>
    
    <?php
    $allOk = true;
    
    // Teste 1: Versão do PHP
    echo '<div class="test-item ok">';
    echo '<strong>✓ PHP Version:</strong> ' . phpversion();
    if (version_compare(phpversion(), '7.4.0', '>=')) {
        echo ' <span class="success">(OK)</span>';
    } else {
        echo ' <span class="error">(Requer PHP 7.4 ou superior)</span>';
        $allOk = false;
    }
    echo '</div>';
    
    // Teste 2: Extensão SQLite3
    echo '<div class="test-item ' . (extension_loaded('sqlite3') ? 'ok' : 'fail') . '">';
    echo '<strong>' . (extension_loaded('sqlite3') ? '✓' : '✗') . ' SQLite3:</strong> ';
    if (extension_loaded('sqlite3')) {
        echo '<span class="success">Habilitado</span>';
        $version = SQLite3::version();
        echo ' (Versão: ' . $version['versionString'] . ')';
    } else {
        echo '<span class="error">NÃO habilitado - Habilite no php.ini</span>';
        $allOk = false;
    }
    echo '</div>';
    
    // Teste 3: Permissões de escrita
    $dbDir = __DIR__ . '/database';
    echo '<div class="test-item ' . (is_writable(__DIR__) ? 'ok' : 'fail') . '">';
    echo '<strong>' . (is_writable(__DIR__) ? '✓' : '✗') . ' Permissões de Escrita:</strong> ';
    if (is_writable(__DIR__)) {
        echo '<span class="success">OK</span>';
    } else {
        echo '<span class="error">Sem permissão para criar banco de dados</span>';
        $allOk = false;
    }
    echo '</div>';
    
    // Teste 4: Criar banco de dados de teste
    if (extension_loaded('sqlite3')) {
        try {
            if (!file_exists($dbDir)) {
                mkdir($dbDir, 0777, true);
            }
            $testDb = new SQLite3($dbDir . '/test.db');
            $testDb->exec('CREATE TABLE IF NOT EXISTS test (id INTEGER)');
            $testDb->close();
            unlink($dbDir . '/test.db');
            
            echo '<div class="test-item ok">';
            echo '<strong>✓ Criação de Banco:</strong> <span class="success">OK</span>';
            echo '</div>';
        } catch (Exception $e) {
            echo '<div class="test-item fail">';
            echo '<strong>✗ Criação de Banco:</strong> <span class="error">ERRO - ' . $e->getMessage() . '</span>';
            echo '</div>';
            $allOk = false;
        }
    }
    
    // Teste 5: Sessões PHP
    echo '<div class="test-item ok">';
    echo '<strong>✓ Sessões PHP:</strong> ';
    if (session_status() === PHP_SESSION_DISABLED) {
        echo '<span class="error">Desabilitadas</span>';
        $allOk = false;
    } else {
        echo '<span class="success">Habilitadas</span>';
    }
    echo '</div>';
    
    // Teste 6: JSON
    echo '<div class="test-item ok">';
    echo '<strong>✓ JSON:</strong> ';
    if (function_exists('json_encode')) {
        echo '<span class="success">Habilitado</span>';
    } else {
        echo '<span class="error">NÃO habilitado</span>';
        $allOk = false;
    }
    echo '</div>';
    
    // Resumo
    echo '<hr>';
    if ($allOk) {
        echo '<h2 class="success">✓ Todos os testes passaram! Sistema pronto para uso.</h2>';
        echo '<p><a href="index.php" style="background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Ir para Planning Poker</a></p>';
    } else {
        echo '<h2 class="error">✗ Alguns problemas foram encontrados. Corrija-os antes de usar.</h2>';
    }
    
    // Informações do sistema
    echo '<hr>';
    echo '<h3>Informações do Sistema:</h3>';
    echo '<pre>';
    echo 'PHP SAPI: ' . php_sapi_name() . "\n";
    echo 'Sistema Operacional: ' . PHP_OS . "\n";
    echo 'Diretório Atual: ' . __DIR__ . "\n";
    echo '</pre>';
    ?>
    
</body>
</html>
