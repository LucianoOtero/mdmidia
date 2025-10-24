<?php
/**
 * CONSULTA LEADS E OPORTUNIDADES POR EMAIL
 * Usa as funções de busca do EspoCRM para verificar se foram criados
 */

echo "=== CONSULTA LEADS E OPORTUNIDADES POR EMAIL ===\n\n";

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
function findLeadByEmail($email)
{
    global $espocrm_url;

    echo "🔍 Buscando LEAD por email: $email\n";

    $url = $espocrm_url . '/api/v1/Lead?where[0][type]=equals&where[0][attribute]=emailAddress&where[0][value]=' . urlencode($email) . '&maxSize=1';

    $result = makeEspoCrmRequest($url);

    echo "HTTP Code: " . $result['http_code'] . "\n";

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
            echo "  Created By: " . ($lead['createdByName'] ?? 'N/A') . "\n";
            echo "  Modified: " . ($lead['modifiedAt'] ?? 'N/A') . "\n";
            return $lead;
        } else {
            echo "❌ Nenhum lead encontrado para o email: $email\n";
            return null;
        }
    } else {
        echo "❌ Erro na busca de lead: HTTP " . $result['http_code'] . "\n";
        echo "Response: " . $result['response'] . "\n";
        return null;
    }
}

// Função para buscar oportunidades por lead ID
function findOpportunitiesByLeadId($lead_id)
{
    global $espocrm_url;

    echo "\n🔍 Buscando OPORTUNIDADES por Lead ID: $lead_id\n";

    $url = $espocrm_url . '/api/v1/Opportunity?where[0][type]=equals&where[0][attribute]=leadId&where[0][value]=' . urlencode($lead_id) . '&maxSize=10';

    $result = makeEspoCrmRequest($url);

    echo "HTTP Code: " . $result['http_code'] . "\n";

    if ($result['http_code'] === 200) {
        $data = json_decode($result['response'], true);
        if ($data && isset($data['list']) && count($data['list']) > 0) {
            echo "✅ OPORTUNIDADES ENCONTRADAS: " . count($data['list']) . "\n\n";
            
            foreach ($data['list'] as $index => $opportunity) {
                echo "Oportunidade " . ($index + 1) . ":\n";
                echo "  ID: " . $opportunity['id'] . "\n";
                echo "  Nome: " . ($opportunity['name'] ?? 'N/A') . "\n";
                echo "  Lead ID: " . ($opportunity['leadId'] ?? 'N/A') . "\n";
                echo "  Stage: " . ($opportunity['stage'] ?? 'N/A') . "\n";
                echo "  Amount: " . ($opportunity['amount'] ?? 'N/A') . "\n";
                echo "  Probability: " . ($opportunity['probability'] ?? 'N/A') . "\n";
                echo "  Lead Source: " . ($opportunity['leadSource'] ?? 'N/A') . "\n";
                echo "  Created: " . ($opportunity['createdAt'] ?? 'N/A') . "\n";
                echo "  Created By: " . ($opportunity['createdByName'] ?? 'N/A') . "\n";
                echo "\n";
            }
            
            return $data['list'];
        } else {
            echo "❌ Nenhuma oportunidade encontrada para o Lead ID: $lead_id\n";
            return [];
        }
    } else {
        echo "❌ Erro na busca de oportunidades: HTTP " . $result['http_code'] . "\n";
        echo "Response: " . $result['response'] . "\n";
        return [];
    }
}

// Função para buscar oportunidades por email (busca indireta)
function findOpportunitiesByEmail($email)
{
    echo "\n🔍 Buscando OPORTUNIDADES por email: $email\n";
    echo "(Busca indireta: email → lead → oportunidades)\n\n";

    // Primeiro buscar o lead por email
    $lead = findLeadByEmail($email);
    
    if ($lead && isset($lead['id'])) {
        // Depois buscar oportunidades por lead ID
        $opportunities = findOpportunitiesByLeadId($lead['id']);
        return $opportunities;
    } else {
        echo "❌ Não foi possível buscar oportunidades - lead não encontrado\n";
        return [];
    }
}

// Função para consulta completa por email
function consultaCompletaPorEmail($email)
{
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "CONSULTA COMPLETA POR EMAIL: $email\n";
    echo str_repeat("=", 60) . "\n";

    // Buscar lead
    $lead = findLeadByEmail($email);
    
    if ($lead) {
        // Buscar oportunidades relacionadas
        $opportunities = findOpportunitiesByLeadId($lead['id']);
        
        echo "\n📊 RESUMO DA CONSULTA:\n";
        echo "✅ Lead encontrado: " . ($lead['firstName'] ?? 'N/A') . " (ID: " . $lead['id'] . ")\n";
        echo "✅ Oportunidades encontradas: " . count($opportunities) . "\n";
        
        if (count($opportunities) > 0) {
            echo "✅ Status: Lead e oportunidades criados com sucesso\n";
        } else {
            echo "⚠️ Status: Lead criado, mas nenhuma oportunidade encontrada\n";
        }
    } else {
        echo "\n❌ RESUMO DA CONSULTA:\n";
        echo "❌ Lead não encontrado\n";
        echo "❌ Status: Nenhum registro encontrado para este email\n";
    }
    
    echo str_repeat("=", 60) . "\n";
}

// EXECUTAR CONSULTAS
echo "Digite o email para consultar (ou pressione Enter para usar email de teste):\n";
$email = trim(fgets(STDIN));

if (empty($email)) {
    // Usar email de teste do último teste
    $email = 'teste.curl.1761260435@teste.com';
    echo "Usando email de teste: $email\n";
}

// Executar consulta completa
consultaCompletaPorEmail($email);

echo "\n=== CONSULTA CONCLUÍDA ===\n";
?>
