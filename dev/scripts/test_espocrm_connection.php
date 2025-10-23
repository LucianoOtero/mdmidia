<?php

/**
 * TESTE DE CONECTIVIDADE ESPOCRM DESENVOLVIMENTO
 * dev/scripts/test_espocrm_connection.php
 * 
 * Script para testar a conexão com o EspoCRM de desenvolvimento
 */

// Incluir configurações
require_once __DIR__ . '/../config/espocrm_dev_credentials.php';

echo "🧪 TESTE DE CONECTIVIDADE ESPOCRM DESENVOLVIMENTO\n";
echo "===============================================\n\n";

// Função para testar conexão
function testEspoCrmConnection()
{
    global $DEV_ESPOCRM_CREDENTIALS;

    $url = $DEV_ESPOCRM_CREDENTIALS['url'] . '/api/v1/App/user';

    $headers = [
        'X-Api-Key: ' . $DEV_ESPOCRM_CREDENTIALS['api_key'],
        'Content-Type: application/json'
    ];

    echo "🔗 Testando conexão com: {$DEV_ESPOCRM_CREDENTIALS['url']}\n";
    echo "🔑 Usando API Key: " . substr($DEV_ESPOCRM_CREDENTIALS['api_key'], 0, 8) . "...\n\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "📊 RESULTADO DO TESTE:\n";
    echo "=====================\n";
    echo "HTTP Code: {$httpCode}\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";

    if ($error) {
        echo "❌ Erro cURL: {$error}\n";
    }

    if ($httpCode >= 200 && $httpCode < 300) {
        echo "✅ CONEXÃO ESTABELECIDA COM SUCESSO!\n";
        return true;
    } else {
        echo "❌ FALHA NA CONEXÃO\n";
        return false;
    }
}

// Função para testar criação de Lead
function testLeadCreation()
{
    global $DEV_ESPOCRM_CREDENTIALS;

    $url = $DEV_ESPOCRM_CREDENTIALS['url'] . '/api/v1/Lead';

    $headers = [
        'X-Api-Key: ' . $DEV_ESPOCRM_CREDENTIALS['api_key'],
        'Content-Type: application/json'
    ];

    $testLead = [
        'firstName' => 'Teste Dev',
        'lastName' => 'Webhook',
        'emailAddress' => 'teste-dev@flyingdonkeys.com.br',
        'phoneNumber' => '11999999999',
        'source' => 'Webflow Dev',
        'description' => 'Lead de teste criado pelo script de desenvolvimento'
    ];

    echo "\n🧪 TESTANDO CRIAÇÃO DE LEAD:\n";
    echo "============================\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testLead));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: {$httpCode}\n";
    echo "Response: {$response}\n";

    if ($httpCode >= 200 && $httpCode < 300) {
        echo "✅ LEAD CRIADO COM SUCESSO!\n";
        $responseData = json_decode($response, true);
        return $responseData['id'] ?? null;
    } else {
        echo "❌ FALHA AO CRIAR LEAD\n";
        return null;
    }
}

// Função para testar criação de Opportunity
function testOpportunityCreation($leadId)
{
    global $DEV_ESPOCRM_CREDENTIALS;

    if (!$leadId) {
        echo "❌ Lead ID não disponível para criar Opportunity\n";
        return false;
    }

    $url = $DEV_ESPOCRM_CREDENTIALS['url'] . '/api/v1/Opportunity';

    $headers = [
        'X-Api-Key: ' . $DEV_ESPOCRM_CREDENTIALS['api_key'],
        'Content-Type: application/json'
    ];

    $testOpportunity = [
        'name' => 'Teste Dev Opportunity',
        'leadId' => $leadId,
        'stage' => 'Qualification',
        'amount' => 0,
        'probability' => 10,
        'leadSource' => 'Webflow Dev',
        'description' => 'Opportunity de teste criada pelo script de desenvolvimento'
    ];

    echo "\n🧪 TESTANDO CRIAÇÃO DE OPPORTUNITY:\n";
    echo "===================================\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testOpportunity));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: {$httpCode}\n";
    echo "Response: {$response}\n";

    if ($httpCode >= 200 && $httpCode < 300) {
        echo "✅ OPPORTUNITY CRIADA COM SUCESSO!\n";
        return true;
    } else {
        echo "❌ FALHA AO CRIAR OPPORTUNITY\n";
        return false;
    }
}

// Executar testes
echo "🚀 INICIANDO TESTES...\n\n";

// Teste 1: Conectividade básica
$connectionOk = testEspoCrmConnection();

if ($connectionOk) {
    // Teste 2: Criação de Lead
    $leadId = testLeadCreation();

    // Teste 3: Criação de Opportunity
    testOpportunityCreation($leadId);

    echo "\n🎉 TODOS OS TESTES CONCLUÍDOS!\n";
    echo "==============================\n";
    echo "✅ O EspoCRM de desenvolvimento está pronto para uso\n";
    echo "🔧 Os webhooks podem ser configurados para usar essas credenciais\n";
} else {
    echo "\n❌ FALHA NOS TESTES\n";
    echo "===================\n";
    echo "Verifique:\n";
    echo "1. Se o EspoCRM está rodando em {$DEV_ESPOCRM_CREDENTIALS['url']}\n";
    echo "2. Se o usuário API foi criado corretamente\n";
    echo "3. Se a API Key está configurada\n";
    echo "4. Se as permissões estão corretas\n";
}

echo "\n📋 PRÓXIMOS PASSOS:\n";
echo "===================\n";
echo "1. Execute: php dev/scripts/generate_espocrm_keys.php\n";
echo "2. Configure as credenciais no EspoCRM\n";
echo "3. Execute este teste novamente\n";
echo "4. Atualize os webhooks de desenvolvimento\n";
