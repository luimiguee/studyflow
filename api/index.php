<?php
// API Principal - Roteamento
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Lidar com OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/database.php';

$db = Database::getInstance();
$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Rota de saúde
if ($path === '/api/health' || $path === '/api/index.php/health') {
    $dbConnected = $db->testConnection();
    echo json_encode([
        'status' => 'ok',
        'database' => $dbConnected ? 'connected' : 'disconnected',
        'timestamp' => date('c')
    ]);
    exit();
}

// Rota raiz
if ($path === '/api' || $path === '/api/index.php' || $path === '/api/') {
    echo json_encode([
        'message' => 'StudyFlow API',
        'version' => '1.0.0',
        'endpoints' => [
            'auth' => '/api/auth.php',
            'tasks' => '/api/tasks.php',
            'emails' => '/api/emails.php',
            'health' => '/api/health'
        ]
    ]);
    exit();
}

// As rotas específicas são tratadas nos arquivos auth.php e tasks.php
// Este arquivo serve como entrada principal

echo json_encode(['message' => 'Use /api/auth.php ou /api/tasks.php']);

?>





