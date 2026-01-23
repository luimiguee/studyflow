<?php
/**
 * Script de teste para envio de email
 * 
 * Uso:
 *   php scripts/test-email.php
 *   ou
 *   docker-compose exec web php /var/www/html/scripts/test-email.php
 */

// Verificar se o autoload do Composer existe
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("ERRO: Composer dependencies não instaladas. Execute 'composer install' ou reconstrua o Docker.\n");
}

require_once $autoloadPath;
require_once __DIR__ . '/../config/email.php';
require_once __DIR__ . '/../api/email.php';

echo "=== Teste de Envio de Email ===\n\n";

// Verificar configurações
echo "Configurações:\n";
echo "  SMTP_HOST: " . SMTP_HOST . "\n";
echo "  SMTP_PORT: " . SMTP_PORT . "\n";
echo "  SMTP_USERNAME: " . (SMTP_USERNAME ? SMTP_USERNAME : '(não configurado)') . "\n";
echo "  SMTP_FROM_EMAIL: " . SMTP_FROM_EMAIL . "\n";
echo "  USE_SMTP: " . (USE_SMTP ? 'Sim' : 'Não') . "\n\n";

// Solicitar email de teste
echo "Digite o email de destino para teste (ou pressione Enter para pular): ";
$handle = fopen("php://stdin", "r");
$testEmail = trim(fgets($handle));
fclose($handle);

if (empty($testEmail)) {
    echo "Teste cancelado.\n";
    exit(0);
}

if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
    die("ERRO: Email inválido.\n");
}

echo "\nEnviando email de teste para: $testEmail\n";

try {
    $emailService = EmailService::getInstance();
    
    // Teste 1: Email de boas-vindas
    echo "\n1. Testando email de boas-vindas...\n";
    $result = $emailService->sendWelcomeEmail($testEmail, 'Usuário de Teste');
    
    if ($result['success']) {
        echo "   ✓ Email de boas-vindas enviado com sucesso!\n";
    } else {
        echo "   ✗ Erro ao enviar email de boas-vindas: " . $result['message'] . "\n";
    }
    
    // Teste 2: Email genérico
    echo "\n2. Testando email genérico...\n";
    $result = $emailService->send(
        $testEmail,
        'Teste de Email - StudyFlow',
        '<h1>Teste de Email</h1><p>Este é um email de teste do StudyFlow.</p>',
        'Teste de Email - Este é um email de teste do StudyFlow.'
    );
    
    if ($result['success']) {
        echo "   ✓ Email genérico enviado com sucesso!\n";
    } else {
        echo "   ✗ Erro ao enviar email genérico: " . $result['message'] . "\n";
    }
    
    echo "\n=== Teste concluído ===\n";
    echo "Verifique sua caixa de entrada (e spam) para confirmar o recebimento.\n";
    
} catch (Exception $e) {
    echo "\nERRO: " . $e->getMessage() . "\n";
    exit(1);
}

?>
