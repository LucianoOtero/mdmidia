<?php

/**
 * TESTE DE ENDPOINTS ESPOCRM
 * dev/scripts/test_espocrm_endpoints.php
 * 
 * Script para testar diferentes endpoints de autenticação do EspoCRM
 */

$ESPOCRM_URL = 'https://dev.flyingdonkeys.com.br';

echo "🔍 TESTANDO ENDPOINTS DE AUTENTICAÇÃO ESPOCRM\n";
echo "============================================\n\n";

// Função para testar endpoint
function testEndpoint($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

// Endpoints para testar
$endpoints = [
    '/api/v1/App/login',
    '/api/v1/App/user',
    '/api/v1/User/login',
    '/api/v1/Auth/login',
    '/api/v1/login',
    '/api/v1/App/auth',
    '/api/v1/App/authentication',
    '/api/v1/App/session',
    '/api/v1/session',
    '/api/v1/auth'
];

$loginData = [
    'email' => 'admin@example.com',
    'password' => 'test123'
];

echo "🧪 TESTANDO ENDPOINTS COM MÉTODO POST:\n";
echo "======================================\n";

foreach ($endpoints as $endpoint) {
    $url = $ESPOCRM_URL . $endpoint;
    echo "🔗 Testando: {$endpoint}\n";
    
    $result = testEndpoint($url, 'POST', $loginData);
    
    echo "   HTTP Code: {$result['http_code']}\n";
    echo "   Response: " . substr($result['response'], 0, 100) . "...\n";
    
    if ($result['error']) {
        echo "   Error: {$result['error']}\n";
    }
    
    echo "\n";
}

echo "🧪 TESTANDO ENDPOINTS COM MÉTODO GET:\n";
echo "=====================================\n";

foreach ($endpoints as $endpoint) {
    $url = $ESPOCRM_URL . $endpoint;
    echo "🔗 Testando: {$endpoint}\n";
    
    $result = testEndpoint($url, 'GET');
    
    echo "   HTTP Code: {$result['http_code']}\n";
    echo "   Response: " . substr($result['response'], 0, 100) . "...\n";
    
    if ($result['error']) {
        echo "   Error: {$result['error']}\n";
    }
    
    echo "\n";
}

echo "🔍 TESTANDO ENDPOINTS DE INFORMAÇÕES:\n";
echo "=====================================\n";

$infoEndpoints = [
    '/api/v1/App/info',
    '/api/v1/App/version',
    '/api/v1/App/status',
    '/api/v1/info',
    '/api/v1/version',
    '/api/v1/status',
    '/api/v1/App',
    '/api/v1'
];

foreach ($infoEndpoints as $endpoint) {
    $url = $ESPOCRM_URL . $endpoint;
    echo "🔗 Testando: {$endpoint}\n";
    
    $result = testEndpoint($url, 'GET');
    
    echo "   HTTP Code: {$result['http_code']}\n";
    echo "   Response: " . substr($result['response'], 0, 200) . "...\n";
    
    if ($result['error']) {
        echo "   Error: {$result['error']}\n";
    }
    
    echo "\n";
}

echo "📋 RESUMO:\n";
echo "==========\n";
echo "• Endpoints com HTTP 200: Provavelmente funcionais\n";
echo "• Endpoints com HTTP 405: Método não permitido\n";
echo "• Endpoints com HTTP 404: Não encontrados\n";
echo "• Endpoints com HTTP 500: Erro interno do servidor\n\n";

echo "💡 PRÓXIMOS PASSOS:\n";
echo "===================\n";
echo "1. Identifique o endpoint que retorna HTTP 200\n";
echo "2. Use esse endpoint para autenticação\n";
echo "3. Verifique a documentação da API do EspoCRM\n";

?>
