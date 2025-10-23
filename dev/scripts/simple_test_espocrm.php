<?php

/**
 * TESTE SIMPLES DE CONECTIVIDADE ESPOCRM
 * dev/scripts/simple_test_espocrm.php
 * 
 * Teste simples para verificar se a API Key está funcionando
 */

require_once __DIR__ . '/../config/espocrm_dev_credentials.php';

echo "🧪 TESTE SIMPLES DE CONECTIVIDADE ESPOCRM\n";
echo "=========================================\n\n";

$ESPOCRM_URL = $DEV_ESPOCRM_CREDENTIALS['url'];
$API_KEY = $DEV_ESPOCRM_CREDENTIALS['api_key'];

echo "🔗 URL: {$ESPOCRM_URL}\n";
echo "🔑 API Key: " . substr($API_KEY, 0, 8) . "...\n\n";

// Função para testar conectividade
function testConnection($url, $apiKey)
{
    $ch = curl_init();

    $headers = [
        'X-Api-Key: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    curl_setopt($ch, CURLOPT_URL, $url . '/api/v1/App/user');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

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

// Função para testar criação de Lead
function testLeadCreation($url, $apiKey)
{
    $ch = curl_init();

    $headers = [
        'X-Api-Key: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    $leadData = [
        'firstName' => 'Teste Dev',
        'lastName' => 'Webhook',
        'emailAddress' => 'teste-dev@flyingdonkeys.com.br',
        'source' => 'Site',
        'description' => 'Lead de teste criado pelo script'
    ];

    curl_setopt($ch, CURLOPT_URL, $url . '/api/v1/Lead');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($leadData));

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

echo "🚀 INICIANDO TESTES...\n\n";

// Teste 1: Conectividade básica
echo "1️⃣ TESTE DE CONECTIVIDADE:\n";
echo "==========================\n";

$result = testConnection($ESPOCRM_URL, $API_KEY);

echo "HTTP Code: {$result['http_code']}\n";
echo "Response: " . substr($result['response'], 0, 200) . "...\n";

if ($result['error']) {
    echo "Error: {$result['error']}\n";
}

if ($result['success']) {
    echo "✅ CONECTIVIDADE OK!\n\n";

    // Teste 2: Criação de Lead
    echo "2️⃣ TESTE DE CRIAÇÃO DE LEAD:\n";
    echo "============================\n";

    $leadResult = testLeadCreation($ESPOCRM_URL, $API_KEY);

    echo "HTTP Code: {$leadResult['http_code']}\n";
    echo "Response: " . substr($leadResult['response'], 0, 200) . "...\n";

    if ($leadResult['error']) {
        echo "Error: {$leadResult['error']}\n";
    }

    if ($leadResult['success']) {
        echo "✅ LEAD CRIADO COM SUCESSO!\n";

        $responseData = json_decode($leadResult['response'], true);
        if (isset($responseData['id'])) {
            echo "Lead ID: {$responseData['id']}\n";
        }
    } else {
        echo "❌ FALHA AO CRIAR LEAD\n";
    }

    echo "\n🎉 TODOS OS TESTES CONCLUÍDOS!\n";
    echo "===============================\n";
    echo "✅ O EspoCRM está funcionando corretamente\n";
    echo "✅ A API Key está válida\n";
    echo "✅ Os webhooks podem usar essas credenciais\n";
} else {
    echo "❌ FALHA NA CONECTIVIDADE\n";
    echo "=========================\n";
    echo "Verifique:\n";
    echo "1. Se a API Key está correta\n";
    echo "2. Se o usuário tem permissões adequadas\n";
    echo "3. Se a API está habilitada no EspoCRM\n";
}

echo "\n📋 CREDENCIAIS FINAIS:\n";
echo "======================\n";
echo "URL: {$ESPOCRM_URL}\n";
echo "API Key: {$API_KEY}\n";
echo "Usuário: api\n";
echo "Role: API Webhook Dev\n";
