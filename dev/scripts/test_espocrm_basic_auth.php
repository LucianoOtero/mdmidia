<?php

/**
 * TESTE DE AUTENTICAÇÃO ESPOCRM COM BASIC AUTH
 * dev/scripts/test_espocrm_basic_auth.php
 * 
 * Script para testar autenticação básica no EspoCRM
 */

$ESPOCRM_URL = 'https://dev.flyingdonkeys.com.br';

echo "🔐 TESTANDO AUTENTICAÇÃO BÁSICA ESPOCRM\n";
echo "======================================\n\n";

// Solicitar credenciais
echo "Digite as credenciais do administrador:\n";
$email = readline("📧 Email: ");
$password = readline("🔒 Senha: ");

echo "\n🧪 TESTANDO DIFERENTES MÉTODOS DE AUTENTICAÇÃO:\n";
echo "===============================================\n";

// Função para testar com Basic Auth
function testBasicAuth($url, $email, $password) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode($email . ':' . $password)
    ];
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
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

// Função para testar com POST + Basic Auth
function testPostBasicAuth($url, $email, $password, $data = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode($email . ':' . $password)
    ];
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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

// Endpoints para testar com Basic Auth
$endpoints = [
    '/api/v1/App/user',
    '/api/v1/App/info',
    '/api/v1/User',
    '/api/v1/App',
    '/api/v1'
];

echo "🔑 TESTANDO COM BASIC AUTH (GET):\n";
echo "=================================\n";

foreach ($endpoints as $endpoint) {
    $url = $ESPOCRM_URL . $endpoint;
    echo "🔗 Testando: {$endpoint}\n";
    
    $result = testBasicAuth($url, $email, $password);
    
    echo "   HTTP Code: {$result['http_code']}\n";
    echo "   Response: " . substr($result['response'], 0, 200) . "...\n";
    
    if ($result['error']) {
        echo "   Error: {$result['error']}\n";
    }
    
    if ($result['http_code'] === 200) {
        echo "   ✅ SUCESSO! Este endpoint funciona!\n";
    }
    
    echo "\n";
}

echo "🔑 TESTANDO COM BASIC AUTH (POST):\n";
echo "===================================\n";

$loginData = [
    'email' => $email,
    'password' => $password
];

foreach ($endpoints as $endpoint) {
    $url = $ESPOCRM_URL . $endpoint;
    echo "🔗 Testando: {$endpoint}\n";
    
    $result = testPostBasicAuth($url, $email, $password, $loginData);
    
    echo "   HTTP Code: {$result['http_code']}\n";
    echo "   Response: " . substr($result['response'], 0, 200) . "...\n";
    
    if ($result['error']) {
        echo "   Error: {$result['error']}\n";
    }
    
    if ($result['http_code'] === 200) {
        echo "   ✅ SUCESSO! Este endpoint funciona!\n";
    }
    
    echo "\n";
}

echo "🔍 TESTANDO ENDPOINTS ESPECÍFICOS:\n";
echo "==================================\n";

$specificEndpoints = [
    '/api/v1/App/user' => 'GET',
    '/api/v1/App/user' => 'POST',
    '/api/v1/User' => 'GET',
    '/api/v1/User' => 'POST',
    '/api/v1/App/info' => 'GET',
    '/api/v1/App/version' => 'GET'
];

foreach ($specificEndpoints as $endpoint => $method) {
    $url = $ESPOCRM_URL . $endpoint;
    echo "🔗 Testando: {$endpoint} ({$method})\n";
    
    if ($method === 'GET') {
        $result = testBasicAuth($url, $email, $password);
    } else {
        $result = testPostBasicAuth($url, $email, $password, $loginData);
    }
    
    echo "   HTTP Code: {$result['http_code']}\n";
    echo "   Response: " . substr($result['response'], 0, 200) . "...\n";
    
    if ($result['error']) {
        echo "   Error: {$result['error']}\n";
    }
    
    if ($result['http_code'] === 200) {
        echo "   ✅ SUCESSO! Este endpoint funciona!\n";
        
        // Tentar extrair token se disponível
        $responseData = json_decode($result['response'], true);
        if (isset($responseData['token'])) {
            echo "   🔑 Token encontrado: " . substr($responseData['token'], 0, 20) . "...\n";
        }
    }
    
    echo "\n";
}

echo "📋 RESUMO:\n";
echo "==========\n";
echo "• HTTP 200: Autenticação bem-sucedida\n";
echo "• HTTP 401: Credenciais inválidas ou endpoint incorreto\n";
echo "• HTTP 403: Acesso negado\n";
echo "• HTTP 405: Método não permitido\n\n";

echo "💡 PRÓXIMOS PASSOS:\n";
echo "===================\n";
echo "1. Se algum endpoint retornar HTTP 200, use-o para autenticação\n";
echo "2. Se retornar token, use-o para requisições subsequentes\n";
echo "3. Se não funcionar, pode ser necessário configurar o EspoCRM\n";

?>
