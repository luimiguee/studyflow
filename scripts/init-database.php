<?php
// Script para inicializar o banco de dados
require_once __DIR__ . '/../config/database.php';

function initDatabase() {
    try {
        // Conectar ao MySQL sem especificar banco de dados
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "📦 Inicializando banco de dados...\n";

        // Criar banco de dados
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Banco de dados criado: " . DB_NAME . "\n";

        // Usar o banco de dados
        $pdo->exec("USE " . DB_NAME);

        // Ler e executar schema.sql
        $schemaPath = __DIR__ . '/../database/schema.sql';
        if (file_exists($schemaPath)) {
            $schema = file_get_contents($schemaPath);
            
            // Remover comentários e dividir em queries
            $queries = array_filter(
                array_map('trim', explode(';', $schema)),
                function($q) {
                    return !empty($q) && !preg_match('/^--/', $q) && !preg_match('/^\/\*/', $q);
                }
            );

            foreach ($queries as $query) {
                if (!empty(trim($query))) {
                    $pdo->exec($query);
                }
            }
            echo "✅ Schema criado com sucesso!\n";
        } else {
            echo "⚠️  Arquivo schema.sql não encontrado\n";
        }

        echo "✅ Banco de dados inicializado com sucesso!\n";
        return true;
    } catch (PDOException $e) {
        echo "❌ Erro ao inicializar banco de dados: " . $e->getMessage() . "\n";
        return false;
    }
}

// Executar se chamado diretamente
if (php_sapi_name() === 'cli') {
    initDatabase();
} else {
    echo "Este script deve ser executado via linha de comando.\n";
}

?>





