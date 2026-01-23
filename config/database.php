<?php
// Configuração de Banco de Dados
// Este arquivo contém as configurações de conexão com o banco de dados

// Carregar variáveis de ambiente do Docker ou ficheiro .env.php
if (file_exists(__DIR__ . '/../.env.php')) {
    require_once __DIR__ . '/../.env.php';
}

// Carregar variáveis de ambiente do ficheiro .env (para Docker)
if (file_exists(__DIR__ . '/../.env')) {
    $envFile = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envFile as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorar comentários
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!getenv($key)) {
                putenv("$key=$value");
            }
        }
    }
}

// Configurações do banco de dados
// Prioridade: variável de ambiente > .env > valores padrão
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'studyflow');
define('DB_CHARSET', 'utf8mb4');

// Configurações da aplicação
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'seu_secret_jwt_aqui_mude_em_producao');
define('API_URL', getenv('API_URL') ?: 'http://localhost:8000/api');

?>





