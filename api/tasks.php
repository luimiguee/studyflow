<?php
// API de Tarefas
error_reporting(E_ALL);
ini_set('display_errors', 0); // Não exibir erros, mas logá-los

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Lidar com OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/jwt.php';

// Função para verificar token
function getBearerToken() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    
    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        return $matches[1];
    }
    
    return null;
}

function verifyToken() {
    $token = getBearerToken();
    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'Token não fornecido']);
        exit();
    }

    try {
        return JWT::decode($token);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido']);
        exit();
    }
}

// Verificar token
$payload = verifyToken();
$userId = $payload['id'];

$db = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

// Roteamento
switch ($method) {
    case 'GET':
        if ($id) {
            handleGetTask($id, $userId);
        } else {
            handleGetTasks($userId);
        }
        break;

    case 'POST':
        handleCreateTask($userId);
        break;

    case 'PUT':
        if ($id) {
            handleUpdateTask($id, $userId);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID da tarefa é obrigatório']);
        }
        break;

    case 'DELETE':
        if ($id) {
            handleDeleteTask($id, $userId);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID da tarefa é obrigatório']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
}

function handleGetTasks($userId) {
    global $db;
    
    $tasks = $db->fetchAll(
        'SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC',
        [$userId]
    );
    
    echo json_encode(['tasks' => $tasks]);
}

function handleGetTask($id, $userId) {
    global $db;
    
    $task = $db->fetchOne(
        'SELECT * FROM tasks WHERE id = ? AND user_id = ?',
        [$id, $userId]
    );

    if (!$task) {
        http_response_code(404);
        echo json_encode(['error' => 'Tarefa não encontrada']);
        return;
    }

    echo json_encode(['task' => $task]);
}

function handleCreateTask($userId) {
    global $db;
    
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido: ' . json_last_error_msg()]);
            error_log('Erro ao decodificar JSON: ' . json_last_error_msg());
            return;
        }
        
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? null;
        $status = $data['status'] ?? 'pendente';
        $priority = $data['priority'] ?? 'media';
        $dueDate = $data['due_date'] ?? null;

        if (empty($title)) {
            http_response_code(400);
            echo json_encode(['error' => 'Título é obrigatório']);
            return;
        }

        // Verificar se a inserção foi bem-sucedida
        $rowsAffected = $db->execute(
            'INSERT INTO tasks (user_id, title, description, status, priority, due_date) VALUES (?, ?, ?, ?, ?, ?)',
            [$userId, $title, $description, $status, $priority, $dueDate]
        );

        if ($rowsAffected === 0) {
            http_response_code(500);
            echo json_encode(['error' => 'Falha ao inserir tarefa na base de dados']);
            error_log('Erro: Nenhuma linha afetada ao inserir tarefa para user_id: ' . $userId);
            return;
        }

        $taskId = $db->lastInsertId();
        
        if (!$taskId) {
            http_response_code(500);
            echo json_encode(['error' => 'Falha ao obter ID da tarefa inserida']);
            error_log('Erro: Não foi possível obter lastInsertId após inserir tarefa');
            return;
        }

        $task = $db->fetchOne('SELECT * FROM tasks WHERE id = ?', [$taskId]);
        
        if (!$task) {
            http_response_code(500);
            echo json_encode(['error' => 'Tarefa criada mas não foi possível recuperá-la']);
            error_log('Erro: Tarefa com ID ' . $taskId . ' não encontrada após inserção');
            return;
        }

        // Adicionar log (não crítico - continuar mesmo se falhar)
        try {
            $user = $db->fetchOne('SELECT email FROM users WHERE id = ?', [$userId]);
            if ($user) {
                $db->execute(
                    'INSERT INTO activity_logs (user_id, user_email, action, details) VALUES (?, ?, ?, ?)',
                    [$userId, $user['email'], 'Criação de Tarefa', "Tarefa criada: $title"]
                );
            }
        } catch (Exception $logError) {
            // Log não crítico - apenas registrar erro mas continuar
            error_log('Aviso: Erro ao inserir log de atividade: ' . $logError->getMessage());
        }

        http_response_code(201);
        echo json_encode(['task' => $task]);
        
    } catch (Exception $e) {
        http_response_code(500);
        error_log('Erro ao criar tarefa: ' . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
        echo json_encode(['error' => 'Erro interno do servidor ao criar tarefa']);
    }
}

function handleUpdateTask($id, $userId) {
    global $db;
    
    $existing = $db->fetchOne(
        'SELECT * FROM tasks WHERE id = ? AND user_id = ?',
        [$id, $userId]
    );

    if (!$existing) {
        http_response_code(404);
        echo json_encode(['error' => 'Tarefa não encontrada']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'] ?? $existing['title'];
    $description = $data['description'] ?? $existing['description'];
    $status = $data['status'] ?? $existing['status'];
    $priority = $data['priority'] ?? $existing['priority'];
    $dueDate = $data['due_date'] ?? $existing['due_date'];

    $db->execute(
        'UPDATE tasks SET title = ?, description = ?, status = ?, priority = ?, due_date = ? WHERE id = ? AND user_id = ?',
        [$title, $description, $status, $priority, $dueDate, $id, $userId]
    );

    $task = $db->fetchOne('SELECT * FROM tasks WHERE id = ?', [$id]);

    // Adicionar log
    $user = $db->fetchOne('SELECT email FROM users WHERE id = ?', [$userId]);
    $db->execute(
        'INSERT INTO activity_logs (user_id, user_email, action, details) VALUES (?, ?, ?, ?)',
        [$userId, $user['email'], 'Atualização de Tarefa', "Tarefa atualizada: $title"]
    );

    echo json_encode(['task' => $task]);
}

function handleDeleteTask($id, $userId) {
    global $db;
    
    $existing = $db->fetchOne(
        'SELECT * FROM tasks WHERE id = ? AND user_id = ?',
        [$id, $userId]
    );

    if (!$existing) {
        http_response_code(404);
        echo json_encode(['error' => 'Tarefa não encontrada']);
        return;
    }

    $db->execute('DELETE FROM tasks WHERE id = ? AND user_id = ?', [$id, $userId]);

    // Adicionar log
    $user = $db->fetchOne('SELECT email FROM users WHERE id = ?', [$userId]);
    $db->execute(
        'INSERT INTO activity_logs (user_id, user_email, action, details) VALUES (?, ?, ?, ?)',
        [$userId, $user['email'], 'Exclusão de Tarefa', "Tarefa deletada: {$existing['title']}"]
    );

        echo json_encode(['success' => true, 'message' => 'Tarefa eliminada com sucesso']);
}

?>

