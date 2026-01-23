<?php
// API de Administração
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

function verifyAdminToken() {
    $token = getBearerToken();
    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'Token não fornecido']);
        exit();
    }

    try {
        $payload = JWT::decode($token);
        if ($payload['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado. Apenas administradores.']);
            exit();
        }
        return $payload;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido']);
        exit();
    }
}

// Verificar se é admin
$payload = verifyAdminToken();
$db = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Roteamento
switch ($method) {
    case 'GET':
        switch ($action) {
            case 'stats':
                handleGetStats();
                break;
            case 'users':
                handleGetUsers();
                break;
            case 'user':
                handleGetUser($_GET['id'] ?? null);
                break;
            case 'logs':
                handleGetLogs();
                break;
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Ação não encontrada']);
        }
        break;

    case 'POST':
        switch ($action) {
            case 'create-user':
                handleCreateUser();
                break;
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Ação não encontrada']);
        }
        break;

    case 'PUT':
        switch ($action) {
            case 'update-user':
                handleUpdateUser($_GET['id'] ?? null);
                break;
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Ação não encontrada']);
        }
        break;

    case 'DELETE':
        switch ($action) {
            case 'delete-user':
                handleDeleteUser($_GET['id'] ?? null);
                break;
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Ação não encontrada']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
}

function handleGetStats() {
    global $db;
    
    try {
        // Estatísticas gerais
        $totalUsers = $db->fetchOne('SELECT COUNT(*) as count FROM users')['count'];
        $totalTasks = $db->fetchOne('SELECT COUNT(*) as count FROM tasks')['count'];
        $completedTasks = $db->fetchOne("SELECT COUNT(*) as count FROM tasks WHERE status = 'concluida'")['count'];
        $pendingTasks = $db->fetchOne("SELECT COUNT(*) as count FROM tasks WHERE status = 'pendente'")['count'];
        $totalLogs = $db->fetchOne('SELECT COUNT(*) as count FROM activity_logs')['count'];
        
        // Utilizadores por role
        $usersByRole = $db->fetchAll('SELECT role, COUNT(*) as count FROM users GROUP BY role');
        
        // Tarefas por status
        $tasksByStatus = $db->fetchAll('SELECT status, COUNT(*) as count FROM tasks GROUP BY status');
        
        // Tarefas por prioridade
        $tasksByPriority = $db->fetchAll('SELECT priority, COUNT(*) as count FROM tasks GROUP BY priority');
        
        // Atividades recentes (últimos 7 dias)
        $recentActivities = $db->fetchAll(
            "SELECT COUNT(*) as count, DATE(created_at) as date 
             FROM activity_logs 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(created_at)
             ORDER BY date DESC"
        );
        
        // Utilizadores mais activos
        $mostActiveUsers = $db->fetchAll(
            "SELECT u.id, u.name, u.email, COUNT(al.id) as activity_count
             FROM users u
             LEFT JOIN activity_logs al ON u.id = al.user_id
             GROUP BY u.id, u.name, u.email
             ORDER BY activity_count DESC
             LIMIT 5"
        );
        
        echo json_encode([
            'stats' => [
                'users' => [
                    'total' => (int)$totalUsers,
                    'byRole' => $usersByRole
                ],
                'tasks' => [
                    'total' => (int)$totalTasks,
                    'completed' => (int)$completedTasks,
                    'pending' => (int)$pendingTasks,
                    'byStatus' => $tasksByStatus,
                    'byPriority' => $tasksByPriority
                ],
                'logs' => [
                    'total' => (int)$totalLogs
                ],
                'recentActivities' => $recentActivities,
                'mostActiveUsers' => $mostActiveUsers
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao obter estatísticas: ' . $e->getMessage()]);
    }
}

function handleGetUsers() {
    global $db;
    
    try {
        $users = $db->fetchAll(
            "SELECT id, name, email, role, created_at, 
             (SELECT COUNT(*) FROM tasks WHERE user_id = users.id) as task_count,
             (SELECT COUNT(*) FROM activity_logs WHERE user_id = users.id) as log_count
             FROM users 
             ORDER BY created_at DESC"
        );
        
        echo json_encode(['users' => $users]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao obter utilizadores: ' . $e->getMessage()]);
    }
}

function handleGetUser($id) {
    global $db;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID do utilizador é obrigatório']);
        return;
    }
    
    try {
        $user = $db->fetchOne(
            "SELECT id, name, email, role, created_at FROM users WHERE id = ?",
            [$id]
        );
        
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilizador não encontrado']);
            return;
        }
        
        echo json_encode(['user' => $user]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao obter utilizador: ' . $e->getMessage()]);
    }
}

function handleCreateUser() {
    global $db, $payload;
    
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? 'estudante';
    
    if (empty($name) || empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Nome, e-mail e palavra-passe são obrigatórios']);
        return;
    }
    
    if (!in_array($role, ['admin', 'estudante'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Role inválida']);
        return;
    }
    
    try {
        // Verificar se email já existe
        $existing = $db->fetchOne('SELECT id FROM users WHERE email = ?', [$email]);
        if ($existing) {
            http_response_code(400);
            echo json_encode(['error' => 'Este e-mail já está registado']);
            return;
        }
        
        // Hash da senha
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Criar utilizador
        $db->execute(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
            [$name, $email, $hashedPassword, $role]
        );
        
        $userId = $db->lastInsertId();
        $user = $db->fetchOne('SELECT id, name, email, role, created_at FROM users WHERE id = ?', [$userId]);
        
        // Adicionar log
        $admin = $db->fetchOne('SELECT email FROM users WHERE id = ?', [$payload['id']]);
        $db->execute(
            'INSERT INTO activity_logs (user_id, user_email, action, details) VALUES (?, ?, ?, ?)',
            [$payload['id'], $admin['email'], 'Criação de Utilizador', "Utilizador criado: $name ($email)"]
        );
        
        http_response_code(201);
        echo json_encode(['success' => true, 'user' => $user]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao criar utilizador: ' . $e->getMessage()]);
    }
}

function handleUpdateUser($id) {
    global $db, $payload;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID do utilizador é obrigatório']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $existing = $db->fetchOne('SELECT * FROM users WHERE id = ?', [$id]);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilizador não encontrado']);
            return;
        }
        
        $name = $data['name'] ?? $existing['name'];
        $email = $data['email'] ?? $existing['email'];
        $role = $data['role'] ?? $existing['role'];
        
        // Se email mudou, verificar se já existe
        if ($email !== $existing['email']) {
            $emailExists = $db->fetchOne('SELECT id FROM users WHERE email = ? AND id != ?', [$email, $id]);
            if ($emailExists) {
                http_response_code(400);
                echo json_encode(['error' => 'Este e-mail já está registado']);
                return;
            }
        }
        
        // Atualizar senha se fornecida
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $db->execute(
                'UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?',
                [$name, $email, $hashedPassword, $role, $id]
            );
        } else {
            $db->execute(
                'UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?',
                [$name, $email, $role, $id]
            );
        }
        
        $user = $db->fetchOne('SELECT id, name, email, role, created_at FROM users WHERE id = ?', [$id]);
        
        // Adicionar log
        $admin = $db->fetchOne('SELECT email FROM users WHERE id = ?', [$payload['id']]);
        $db->execute(
            'INSERT INTO activity_logs (user_id, user_email, action, details) VALUES (?, ?, ?, ?)',
            [$payload['id'], $admin['email'], 'Actualização de Utilizador', "Utilizador actualizado: $name ($email)"]
        );
        
        echo json_encode(['success' => true, 'user' => $user]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao actualizar utilizador: ' . $e->getMessage()]);
    }
}

function handleDeleteUser($id) {
    global $db, $payload;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID do utilizador é obrigatório']);
        return;
    }
    
    if ($id == $payload['id']) {
        http_response_code(400);
        echo json_encode(['error' => 'Não pode eliminar a sua própria conta']);
        return;
    }
    
    try {
        $user = $db->fetchOne('SELECT * FROM users WHERE id = ?', [$id]);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilizador não encontrado']);
            return;
        }
        
        $db->execute('DELETE FROM users WHERE id = ?', [$id]);
        
        // Adicionar log
        $admin = $db->fetchOne('SELECT email FROM users WHERE id = ?', [$payload['id']]);
        $db->execute(
            'INSERT INTO activity_logs (user_id, user_email, action, details) VALUES (?, ?, ?, ?)',
            [$payload['id'], $admin['email'], 'Eliminação de Utilizador', "Utilizador eliminado: {$user['name']} ({$user['email']})"]
        );
        
        echo json_encode(['success' => true, 'message' => 'Utilizador eliminado com sucesso']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao eliminar utilizador: ' . $e->getMessage()]);
    }
}

function handleGetLogs() {
    global $db;
    
    try {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        $offset = ($page - 1) * $limit;
        
        $search = $_GET['search'] ?? '';
        $userId = $_GET['user_id'] ?? null;
        $action = $_GET['action'] ?? null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(details LIKE ? OR user_email LIKE ? OR action LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($userId) {
            $conditions[] = "user_id = ?";
            $params[] = $userId;
        }
        
        if ($action) {
            $conditions[] = "action = ?";
            $params[] = $action;
        }
        
        if ($startDate) {
            $conditions[] = "DATE(created_at) >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $conditions[] = "DATE(created_at) <= ?";
            $params[] = $endDate;
        }
        
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        // Total de logs
        $totalLogs = $db->fetchOne("SELECT COUNT(*) as count FROM activity_logs $whereClause", $params)['count'];
        
        // Logs com paginação
        $logs = $db->fetchAll(
            "SELECT al.*, u.name as user_name 
             FROM activity_logs al
             LEFT JOIN users u ON al.user_id = u.id
             $whereClause
             ORDER BY al.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$limit, $offset])
        );
        
        echo json_encode([
            'logs' => $logs,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => (int)$totalLogs,
                'pages' => ceil($totalLogs / $limit)
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao obter logs: ' . $e->getMessage()]);
    }
}

?>

