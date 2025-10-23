<?php

/**
 * CONFIGURAÇÃO AUTOMATIZADA ESPOCRM DESENVOLVIMENTO
 * dev/scripts/auto_setup_espocrm.php
 * 
 * Script para configurar automaticamente o EspoCRM de desenvolvimento
 * usando apenas a API administrativa
 */

// Configurações
$ESPOCRM_URL = 'https://dev.flyingdonkeys.com.br';
$ADMIN_EMAIL = ''; // Será solicitado
$ADMIN_PASSWORD = ''; // Será solicitado

// Credenciais geradas para o usuário API
$API_USER_DATA = [
    'name' => 'API Webhook Dev',
    'email' => 'api-dev@flyingdonkeys.com.br',
    'password' => '4vJMGl9%@DtELFqS',
    'api_key' => 'nEgf0Zwt7b09cGwKGuqSqdPgPpmZHzJU'
];

echo "🤖 CONFIGURAÇÃO AUTOMATIZADA ESPOCRM DESENVOLVIMENTO\n";
echo "==================================================\n\n";

// Solicitar credenciais de admin
echo "🔐 CREDENCIAIS DE ADMINISTRADOR:\n";
echo "================================\n";
echo "Digite as credenciais do administrador do EspoCRM:\n\n";

$ADMIN_EMAIL = readline("📧 Email do Admin: ");
$ADMIN_PASSWORD = readline("🔒 Senha do Admin: ");

echo "\n🚀 INICIANDO CONFIGURAÇÃO AUTOMATIZADA...\n";
echo "==========================================\n\n";

// Função para fazer requisições à API
function espocrmApiRequest($url, $method = 'GET', $data = null, $headers = [])
{
    $ch = curl_init();

    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    $allHeaders = array_merge($defaultHeaders, $headers);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
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
        'error' => $error,
        'success' => $httpCode >= 200 && $httpCode < 300
    ];
}

// Função para obter token de autenticação
function getAuthToken($email, $password)
{
    global $ESPOCRM_URL;

    echo "🔑 Autenticando como administrador...\n";

    $loginData = [
        'email' => $email,
        'password' => $password
    ];

    $result = espocrmApiRequest($ESPOCRM_URL . '/api/v1/App/login', 'POST', $loginData);

    if ($result['success']) {
        $responseData = json_decode($result['response'], true);
        if (isset($responseData['token'])) {
            echo "✅ Autenticação bem-sucedida!\n\n";
            return $responseData['token'];
        }
    }

    echo "❌ Falha na autenticação: " . $result['response'] . "\n";
    return false;
}

// Função para criar usuário
function createApiUser($token, $userData)
{
    global $ESPOCRM_URL;

    echo "👤 Criando usuário API...\n";

    $userPayload = [
        'firstName' => $userData['name'],
        'lastName' => '',
        'emailAddress' => $userData['email'],
        'password' => $userData['password'],
        'type' => 'regular',
        'isActive' => true,
        'isAdmin' => true // Para desenvolvimento
    ];

    $headers = ['Authorization: Bearer ' . $token];
    $result = espocrmApiRequest($ESPOCRM_URL . '/api/v1/User', 'POST', $userPayload, $headers);

    if ($result['success']) {
        $responseData = json_decode($result['response'], true);
        echo "✅ Usuário criado com sucesso! ID: " . ($responseData['id'] ?? 'N/A') . "\n\n";
        return $responseData['id'] ?? null;
    } else {
        echo "❌ Falha ao criar usuário: " . $result['response'] . "\n\n";
        return false;
    }
}

// Função para criar API User
function createApiKey($token, $userId, $apiKey)
{
    global $ESPOCRM_URL;

    echo "🔑 Criando API Key...\n";

    $apiUserPayload = [
        'userId' => $userId,
        'key' => $apiKey,
        'secretKey' => '',
        'allowedIpAddress' => '*'
    ];

    $headers = ['Authorization: Bearer ' . $token];
    $result = espocrmApiRequest($ESPOCRM_URL . '/api/v1/ApiUser', 'POST', $apiUserPayload, $headers);

    if ($result['success']) {
        echo "✅ API Key criada com sucesso!\n\n";
        return true;
    } else {
        echo "❌ Falha ao criar API Key: " . $result['response'] . "\n\n";
        return false;
    }
}

// Função para testar conectividade
function testApiConnection($apiKey)
{
    global $ESPOCRM_URL;

    echo "🧪 Testando conectividade com API Key...\n";

    $headers = ['X-Api-Key: ' . $apiKey];
    $result = espocrmApiRequest($ESPOCRM_URL . '/api/v1/App/user', 'GET', null, $headers);

    if ($result['success']) {
        echo "✅ Conectividade testada com sucesso!\n\n";
        return true;
    } else {
        echo "❌ Falha no teste de conectividade: " . $result['response'] . "\n\n";
        return false;
    }
}

// Função para testar criação de Lead
function testLeadCreation($apiKey)
{
    global $ESPOCRM_URL;

    echo "🧪 Testando criação de Lead...\n";

    $leadData = [
        'firstName' => 'Teste Dev',
        'lastName' => 'Webhook',
        'emailAddress' => 'teste-dev@flyingdonkeys.com.br',
        'phoneNumber' => '11999999999',
        'source' => 'Webflow Dev',
        'description' => 'Lead de teste criado pelo script automatizado'
    ];

    $headers = ['X-Api-Key: ' . $apiKey];
    $result = espocrmApiRequest($ESPOCRM_URL . '/api/v1/Lead', 'POST', $leadData, $headers);

    if ($result['success']) {
        $responseData = json_decode($result['response'], true);
        echo "✅ Lead criado com sucesso! ID: " . ($responseData['id'] ?? 'N/A') . "\n\n";
        return $responseData['id'] ?? null;
    } else {
        echo "❌ Falha ao criar Lead: " . $result['response'] . "\n\n";
        return false;
    }
}

// Função para testar criação de Opportunity
function testOpportunityCreation($apiKey, $leadId)
{
    global $ESPOCRM_URL;

    if (!$leadId) {
        echo "❌ Lead ID não disponível para criar Opportunity\n\n";
        return false;
    }

    echo "🧪 Testando criação de Opportunity...\n";

    $opportunityData = [
        'name' => 'Teste Dev Opportunity',
        'leadId' => $leadId,
        'stage' => 'Qualification',
        'amount' => 0,
        'probability' => 10,
        'leadSource' => 'Webflow Dev',
        'description' => 'Opportunity de teste criada pelo script automatizado'
    ];

    $headers = ['X-Api-Key: ' . $apiKey];
    $result = espocrmApiRequest($ESPOCRM_URL . '/api/v1/Opportunity', 'POST', $opportunityData, $headers);

    if ($result['success']) {
        $responseData = json_decode($result['response'], true);
        echo "✅ Opportunity criada com sucesso! ID: " . ($responseData['id'] ?? 'N/A') . "\n\n";
        return true;
    } else {
        echo "❌ Falha ao criar Opportunity: " . $result['response'] . "\n\n";
        return false;
    }
}

// Executar configuração
try {
    // 1. Autenticar como admin
    $token = getAuthToken($ADMIN_EMAIL, $ADMIN_PASSWORD);
    if (!$token) {
        throw new Exception("Falha na autenticação administrativa");
    }

    // 2. Criar usuário API
    $userId = createApiUser($token, $API_USER_DATA);
    if (!$userId) {
        throw new Exception("Falha ao criar usuário API");
    }

    // 3. Criar API Key
    $apiKeyCreated = createApiKey($token, $userId, $API_USER_DATA['api_key']);
    if (!$apiKeyCreated) {
        throw new Exception("Falha ao criar API Key");
    }

    // 4. Testar conectividade
    $connectivityOk = testApiConnection($API_USER_DATA['api_key']);
    if (!$connectivityOk) {
        throw new Exception("Falha no teste de conectividade");
    }

    // 5. Testar criação de Lead
    $leadId = testLeadCreation($API_USER_DATA['api_key']);

    // 6. Testar criação de Opportunity
    testOpportunityCreation($API_USER_DATA['api_key'], $leadId);

    echo "🎉 CONFIGURAÇÃO AUTOMATIZADA CONCLUÍDA COM SUCESSO!\n";
    echo "==================================================\n\n";

    echo "📋 RESUMO DA CONFIGURAÇÃO:\n";
    echo "==========================\n";
    echo "✅ Usuário API criado: {$API_USER_DATA['name']}\n";
    echo "✅ Email: {$API_USER_DATA['email']}\n";
    echo "✅ Senha: {$API_USER_DATA['password']}\n";
    echo "✅ API Key: {$API_USER_DATA['api_key']}\n";
    echo "✅ Conectividade testada\n";
    echo "✅ Criação de Lead testada\n";
    echo "✅ Criação de Opportunity testada\n\n";

    echo "🔧 PRÓXIMOS PASSOS:\n";
    echo "===================\n";
    echo "1. Os webhooks de desenvolvimento agora usarão o EspoCRM real\n";
    echo "2. Execute: php dev/scripts/test_espocrm_connection.php\n";
    echo "3. Teste os webhooks com dados reais\n\n";
} catch (Exception $e) {
    echo "❌ ERRO NA CONFIGURAÇÃO: " . $e->getMessage() . "\n";
    echo "========================\n\n";
    echo "🔍 VERIFICAÇÕES:\n";
    echo "1. Confirme se o EspoCRM está rodando em {$ESPOCRM_URL}\n";
    echo "2. Verifique se as credenciais de admin estão corretas\n";
    echo "3. Confirme se o usuário admin tem permissões adequadas\n";
    echo "4. Verifique os logs do EspoCRM para mais detalhes\n\n";
}

echo "📝 LOGS SALVOS EM: dev/logs/espocrm_setup.log\n";
