<?php
// API de Email
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Lidar com OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/email.php';
require_once __DIR__ . '/jwt.php';

$method = $_SERVER['REQUEST_METHOD'];

// Parse da URL para obter action
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$queryString = parse_url($requestUri, PHP_URL_QUERY);
parse_str($queryString ?? '', $queryParams);
$action = $queryParams['action'] ?? '';

// Verificar autenticação para ações protegidas
$token = null;
$payload = null;

if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        $token = $matches[1];
        try {
            $payload = JWT::decode($token);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido']);
            exit();
        }
    }
}

// Roteamento
switch ($method) {
    case 'POST':
        if ($action === 'send') {
            handleSendEmail();
        } elseif ($action === 'welcome') {
            handleSendWelcomeEmail();
        } elseif ($action === 'notification') {
            handleSendNotification();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ação não encontrada']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
}

function handleSendEmail() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido: ' . json_last_error_msg()]);
        return;
    }
    
    $to = trim($data['to'] ?? '');
    $subject = trim($data['subject'] ?? '');
    $body = $data['body'] ?? '';
    $altBody = $data['altBody'] ?? '';
    
    if (empty($to) || empty($subject) || empty($body)) {
        http_response_code(400);
        echo json_encode(['error' => 'Destinatário, assunto e corpo são obrigatórios']);
        return;
    }
    
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Formato de e-mail inválido']);
        return;
    }
    
    $emailService = EmailService::getInstance();
    $result = $emailService->send($to, $subject, $body, $altBody);
    
    if ($result['success']) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(500);
        echo json_encode($result);
    }
}

function handleSendWelcomeEmail() {
    global $payload;
    
    // Verificar autenticação
    if (!$payload) {
        http_response_code(401);
        echo json_encode(['error' => 'Autenticação necessária']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido: ' . json_last_error_msg()]);
        return;
    }
    
    $email = trim($data['email'] ?? '');
    $name = trim($data['name'] ?? '');
    
    if (empty($email) || empty($name)) {
        http_response_code(400);
        echo json_encode(['error' => 'E-mail e nome são obrigatórios']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Formato de e-mail inválido']);
        return;
    }
    
    $emailService = EmailService::getInstance();
    $result = $emailService->sendWelcomeEmail($email, $name);
    
    if ($result['success']) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(500);
        echo json_encode($result);
    }
}

function handleSendNotification() {
    global $payload;
    
    // Verificar autenticação
    if (!$payload) {
        http_response_code(401);
        echo json_encode(['error' => 'Autenticação necessária']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido: ' . json_last_error_msg()]);
        return;
    }
    
    $email = trim($data['email'] ?? '');
    $subject = trim($data['subject'] ?? '');
    $message = $data['message'] ?? '';
    
    if (empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo json_encode(['error' => 'E-mail, assunto e mensagem são obrigatórios']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Formato de e-mail inválido']);
        return;
    }
    
    $emailService = EmailService::getInstance();
    $result = $emailService->sendNotification($email, $subject, $message);
    
    if ($result['success']) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(500);
        echo json_encode($result);
    }
}

?>
