<?php
// Configuração de Email
// Este arquivo contém as configurações de email

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

// Configurações de Email SMTP
// Prioridade: variável de ambiente > .env > valores padrão
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: '587');
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: '');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: '');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: 'noreply@studyflow.com');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'StudyFlow');
define('SMTP_ENCRYPTION', getenv('SMTP_ENCRYPTION') ?: 'tls'); // 'tls' ou 'ssl'
define('SMTP_AUTH', getenv('SMTP_AUTH') !== 'false'); // true por padrão

// Para desenvolvimento local, você pode usar mail() do PHP
define('USE_SMTP', getenv('USE_SMTP') !== 'false'); // true por padrão

?>
