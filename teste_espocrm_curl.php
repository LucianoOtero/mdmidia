<?php

/**
 * TESTE DE FUNÇÕES DE BUSCA ESPOCRM - CURL DIRETO
 * Testa as funções de busca do EspoCRM de desenvolvimento usando cURL
 */

echo "=== TESTE DE FUNÇÕES DE BUSCA ESPOCRM ===\n\n";

// Configurações do EspoCRM de desenvolvimento
$espocrm_url = 'https://dev.flyingdonkeys.com.br';
$api_key = 'd538e606685cecd0d76746906468eba4';
$api_username = 'api-dev@flyingdonkeys.com.br';

echo "URL do EspoCRM: $espocrm_url\n";
echo "API Key: " . substr($api_key, 0, 8) . "...\n";
echo "API Username: $api_username\n\n";

// Função para fazer requisições cURL
function makeEspoCrmRequest($url, $method = 'GET', $data = null)
{
    global $api_key, $api_username;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Apenas para desenvolvimento
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-Api-Key: ' . $api_key,
        'X-Api-User: ' . $api_username,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'http_code' => $http_code,
        'response' => $response,
        'error' => $error
    ];
}

// Função para buscar lead por email (IDÊNTICA À IMPLEMENTADA)
function findLeadByEmailTest($email)
{
    global $espocrm_url;

    echo "🔍 Buscando lead por email: $email\n";

    $url = $espocrm_url . '/api/v1/Lead?where[0][type]=equals&where[0][attribute]=emailAddress&where[0][value]=' . urlencode($email) . '&maxSize=1';

    echo "URL da busca: $url\n";

    $result = makeEspoCrmRequest($url);

    echo "HTTP Code: " . $result['http_code'] . "\n";
    if ($result['error']) {
        echo "cURL Error: " . $result['error'] . "\n";
    }

    if ($result['http_code'] === 200) {
        $data = json_decode($result['response'], true);
        if ($data && isset($data['list']) && count($data['list']) > 0) {
            $lead = $data['list'][0];
            echo "✅ LEAD ENCONTRADO:\n";
            echo "  ID: " . $lead['id'] . "\n";
            echo "  Nome: " . ($lead['firstName'] ?? 'N/A') . "\n";
            echo "  Email: " . ($lead['emailAddress'] ?? 'N/A') . "\n";
            echo "  Source: " . ($lead['source'] ?? 'N/A') . "\n";
            echo "  Created: " . ($lead['createdAt'] ?? 'N/A') . "\n";
            return $lead;
        } else {
            echo "❌ Nenhum lead encontrado para o email: $email\n";
            return null;
        }
    } else {
        echo "❌ Erro na busca: HTTP " . $result['http_code'] . "\n";
        echo "Response: " . $result['response'] . "\n";
        return null;
    }
}

// Função para listar leads recentes
function listRecentLeads($limit = 5)
{
    global $espocrm_url;

    echo "\n📋 Listando leads recentes (últimos $limit):\n";

    $url = $espocrm_url . '/api/v1/Lead?maxSize=' . $limit . '&sortBy=createdAt&asc=false';

    $result = makeEspoCrmRequest($url);

    echo "HTTP Code: " . $result['http_code'] . "\n";

    if ($result['http_code'] === 200) {
        $data = json_decode($result['response'], true);
        if ($data && isset($data['list'])) {
            echo "Total de leads encontrados: " . count($data['list']) . "\n\n";

            foreach ($data['list'] as $index => $lead) {
                echo "Lead " . ($index + 1) . ":\n";
                echo "  ID: " . $lead['id'] . "\n";
                echo "  Nome: " . ($lead['firstName'] ?? 'N/A') . "\n";
                echo "  Email: " . ($lead['emailAddress'] ?? 'N/A') . "\n";
                echo "  Source: " . ($lead['source'] ?? 'N/A') . "\n";
                echo "  Created: " . $lead['createdAt'] . "\n";
                echo "\n";
            }

            return $data['list'];
        } else {
            echo "❌ Erro ao decodificar resposta\n";
        }
    } else {
        echo "❌ Erro na listagem: HTTP " . $result['http_code'] . "\n";
        echo "Response: " . $result['response'] . "\n";
    }

    return [];
}

// Função para testar criação de lead
function testCreateLead()
{
    global $espocrm_url;

    echo "\n🧪 Testando criação de lead:\n";

    $test_lead_data = [
        'firstName' => 'TESTE CURL ' . date('Y-m-d H:i:s'),
        'emailAddress' => 'teste.curl.' . time() . '@teste.com',
        'source' => 'Site',
        'description' => 'Lead de teste criado via cURL'
    ];

    echo "Dados do lead:\n";
    echo json_encode($test_lead_data, JSON_PRETTY_PRINT) . "\n\n";

    $url = $espocrm_url . '/api/v1/Lead';
    $result = makeEspoCrmRequest($url, 'POST', $test_lead_data);

    echo "HTTP Code: " . $result['http_code'] . "\n";
    if ($result['error']) {
        echo "cURL Error: " . $result['error'] . "\n";
    }

    if ($result['http_code'] === 200 || $result['http_code'] === 201) {
        $response_data = json_decode($result['response'], true);
        if ($response_data && isset($response_data['id'])) {
            echo "✅ LEAD CRIADO COM SUCESSO:\n";
            echo "  ID: " . $response_data['id'] . "\n";
            echo "  Nome: " . ($response_data['firstName'] ?? 'N/A') . "\n";
            echo "  Email: " . ($response_data['emailAddress'] ?? 'N/A') . "\n";
            return $response_data;
        }
    } else {
        echo "❌ Erro na criação: HTTP " . $result['http_code'] . "\n";
        echo "Response: " . $result['response'] . "\n";
    }

    return null;
}

// EXECUTAR TESTES
echo "1. TESTANDO CONECTIVIDADE BÁSICA:\n";
$test_url = $espocrm_url . '/api/v1/App/user';
$result = makeEspoCrmRequest($test_url);
echo "HTTP Code: " . $result['http_code'] . "\n";
if ($result['http_code'] === 200) {
    echo "✅ Conectividade OK\n";
} else {
    echo "❌ Problema de conectividade\n";
    echo "Response: " . $result['response'] . "\n";
    exit;
}

echo "\n2. LISTANDO LEADS RECENTES:\n";
$recent_leads = listRecentLeads(3);

echo "\n3. TESTANDO BUSCA POR EMAIL:\n";
if (!empty($recent_leads) && isset($recent_leads[0]['emailAddress'])) {
    $test_email = $recent_leads[0]['emailAddress'];
    $found_lead = findLeadByEmailTest($test_email);
} else {
    echo "❌ Nenhum lead encontrado para testar busca por email\n";
}

echo "\n4. TESTANDO CRIAÇÃO DE LEAD:\n";
$new_lead = testCreateLead();

if ($new_lead && isset($new_lead['emailAddress'])) {
    echo "\n5. TESTANDO BUSCA DO LEAD CRIADO:\n";
    findLeadByEmailTest($new_lead['emailAddress']);
}

echo "\n=== TESTE CONCLUÍDO ===\n";
