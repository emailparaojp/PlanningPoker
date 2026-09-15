<?php
function getDB() {
    $db_file = __DIR__ . '/../database/poker.db';
    $db_dir = dirname($db_file);
    
    if (!file_exists($db_dir)) {
        mkdir($db_dir, 0777, true);
    }
    
    $db = new SQLite3($db_file);
    $db->busyTimeout(15000);
    
    // Otimizações de performance
    $db->exec('PRAGMA journal_mode = WAL');
    $db->exec('PRAGMA synchronous = NORMAL');
    $db->exec('PRAGMA foreign_keys = ON');
    $db->exec('PRAGMA temp_store = MEMORY');
    $db->exec('PRAGMA cache_size = 10000');
    
    // Criar tabelas
    $db->exec('
        CREATE TABLE IF NOT EXISTS sessions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code TEXT UNIQUE NOT NULL,
            name TEXT NOT NULL,
            sm_name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            active INTEGER DEFAULT 1
        )
    ');
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS stories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            description TEXT,
            number INTEGER NOT NULL,
            status TEXT DEFAULT "pending",
            final_points REAL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (session_id) REFERENCES sessions(id)
        )
    ');
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS votes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            story_id INTEGER NOT NULL,
            voter_name TEXT NOT NULL,
            points REAL NOT NULL,
            voted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (story_id) REFERENCES stories(id)
        )
    ');
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS participants (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            last_activity DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (session_id) REFERENCES sessions(id)
        )
    ');
    
    return $db;
}

function generateSessionCode() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
}

// Fechar conexão ao final
register_shutdown_function(function() {
    global $db;
    if (isset($db) && $db) {
        $db->close();
    }
});
?>
