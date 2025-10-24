<?php
/**
 * CONSULTA ESPOCRM - LEADS E OPORTUNIDADES
 * Verifica se os registros foram criados corretamente
 */

echo "=== CONSULTA ESPOCRM - LEADS E OPORTUNIDADES ===\n\n";

// Configurações do EspoCRM
$espocrm_url = 'https://dev.flyingdonkeys.com.br';
$api_key = 'd538e606685cecd0d76746906468eba4';

// Headers para autenticação
$headers = [
    'Authorization: Basic ' . base64_encode('api:' . $api_key),
    'Content-Type: application/json'
];

// Função para fazer requisições
function makeRequest($url, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'response' => $response,
        'http_code' => $http_code,
        'error' => $error
    ];
}

// 1. Consultar Leads mais recentes
echo "1. CONSULTANDO LEADS MAIS RECENTES:\n";
$leads_url = $espocrm_url . '/api/v1/Lead?maxSize=5&sortBy=createdAt&asc=false';
$leads_result = makeRequest($leads_url, $headers);

echo "HTTP Code: " . $leads_result['http_code'] . "\n";
if ($leads_result['error']) {
    echo "cURL Error: " . $leads_result['error'] . "\n";
}

$leads_data = json_decode($leads_result['response'], true);
if ($leads_data && isset($leads_data['list'])) {
    echo "Total de leads encontrados: " . count($leads_data['list']) . "\n\n";
    
    foreach ($leads_data['list'] as $index => $lead) {
        echo "Lead " . ($index + 1) . ":\n";
        echo "  ID: " . ($lead['id'] ?? 'N/A') . "\n";
        echo "  Nome: " . ($lead['name'] ?? 'N/A') . "\n";
        echo "  First Name: " . ($lead['firstName'] ?? 'N/A') . "\n";
        echo "  Email: " . ($lead['emailAddress'] ?? 'N/A') . "\n";
        echo "  Source: " . ($lead['source'] ?? 'N/A') . "\n";
        echo "  Created: " . ($lead['createdAt'] ?? 'N/A') . "\n";
        echo "  Created By: " . ($lead['createdByName'] ?? 'N/A') . "\n";
        echo "\n";
    }
} else {
    echo "Erro ao decodificar resposta dos leads\n";
    echo "Response: " . $leads_result['response'] . "\n\n";
}

// 2. Consultar Oportunidades mais recentes
echo "2. CONSULTANDO OPORTUNIDADES MAIS RECENTES:\n";
$opportunities_url = $espocrm_url . '/api/v1/Opportunity?maxSize=5&sortBy=createdAt&asc=false';
$opportunities_result = makeRequest($opportunities_url, $headers);

echo "HTTP Code: " . $opportunities_result['http_code'] . "\n";
if ($opportunities_result['error']) {
    echo "cURL Error: " . $opportunities_result['error'] . "\n";
}

$opportunities_data = json_decode($opportunities_result['response'], true);
if ($opportunities_data && isset($opportunities_data['list'])) {
    echo "Total de oportunidades encontradas: " . count($opportunities_data['list']) . "\n\n";
    
    foreach ($opportunities_data['list'] as $index => $opportunity) {
        echo "Oportunidade " . ($index + 1) . ":\n";
        echo "  ID: " . ($opportunity['id'] ?? 'N/A') . "\n";
        echo "  Nome: " . ($opportunity['name'] ?? 'N/A') . "\n";
        echo "  Stage: " . ($opportunity['stage'] ?? 'N/A') . "\n";
        echo "  Lead Source: " . ($opportunity['leadSource'] ?? 'N/A') . "\n";
        echo "  Lead ID: " . ($opportunity['leadId'] ?? 'N/A') . "\n";
        echo "  Amount: " . ($opportunity['amount'] ?? 'N/A') . "\n";
        echo "  Created: " . ($opportunity['createdAt'] ?? 'N/A') . "\n";
        echo "  Created By: " . ($opportunity['createdByName'] ?? 'N/A') . "\n";
        echo "\n";
    }
} else {
    echo "Erro ao decodificar resposta das oportunidades\n";
    echo "Response: " . $opportunities_result['response'] . "\n\n";
}

echo "=== FIM DA CONSULTA ===\n";
?>
