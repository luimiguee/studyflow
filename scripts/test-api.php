<?php
/**
 * Script de teste para verificar métodos HTTP da API
 * 
 * Uso:
 *   php scripts/test-api.php
 *   ou
 *   docker-compose exec web php /var/www/html/scripts/test-api.php
 */

echo "=== Teste de Métodos HTTP da API ===\n\n";

$baseUrl = 'http://localhost:5500/api';

$methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
$endpoints = [
    'auth.php?action=login' => 'POST',
    'tasks.php' => 'GET',
    'health' => 'GET',
    'index.php' => 'GET'
];

foreach ($endpoints as $endpoint => $expectedMethod) {
    $url = $baseUrl . '/' . $endpoint;
    echo "Testando: $url\n";
    
    foreach ($methods as $method) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Access-Control-Request-Method: ' . $method
        ]);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        $status = $httpCode === 405 ? '❌ 405' : ($httpCode < 400 ? '✓' : "⚠️  $httpCode");
        echo "  $method: $status (HTTP $httpCode)\n";
        
        if ($error) {
            echo "    Erro: $error\n";
        }
    }
    echo "\n";
}

echo "=== Teste concluído ===\n";
echo "Se algum método retornar 405, há um problema de configuração.\n";

?>
