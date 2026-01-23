<?php
// Script para inserir usuários padrão
require_once __DIR__ . '/../api/database.php';

function seedDefaultUsers() {
    try {
        $db = Database::getInstance();
        
        echo "🌱 Inserindo usuários padrão...\n";

        // Verificar se usuários já existem
        $existing = $db->fetchAll(
            "SELECT email FROM users WHERE email IN ('admin@studyflow.com', 'estudante@studyflow.com')"
        );
        
        $existingEmails = array_column($existing, 'email');

        // Hash das senhas
        $adminPasswordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $estudantePasswordHash = password_hash('estudante123', PASSWORD_DEFAULT);

        // Inserir admin
        if (!in_array('admin@studyflow.com', $existingEmails)) {
            $db->execute(
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)",
                ['Admin', 'admin@studyflow.com', $adminPasswordHash, 'admin']
            );
            echo "✅ Usuário admin criado: admin@studyflow.com / admin123\n";
        } else {
            echo "ℹ️  Usuário admin já existe\n";
        }

        // Inserir estudante
        if (!in_array('estudante@studyflow.com', $existingEmails)) {
            $db->execute(
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)",
                ['Estudante Exemplo', 'estudante@studyflow.com', $estudantePasswordHash, 'estudante']
            );
            echo "✅ Usuário estudante criado: estudante@studyflow.com / estudante123\n";
        } else {
            echo "ℹ️  Usuário estudante já existe\n";
        }

        echo "✅ Usuários padrão configurados!\n";
        return true;
    } catch (Exception $e) {
        echo "❌ Erro ao inserir usuários padrão: " . $e->getMessage() . "\n";
        return false;
    }
}

// Executar se chamado diretamente
if (php_sapi_name() === 'cli') {
    seedDefaultUsers();
} else {
    echo "Este script deve ser executado via linha de comando.\n";
}

?>

<｜tool▁calls▁begin｜><｜tool▁call▁begin｜>
read_file




