<?php
/**
 * CONSULTA COMPLETA ESPOCRM - VERIFICAÇÃO DE CAMPOS
 * Verifica se todos os campos enviados estão corretos no EspoCRM
 */

echo "=== CONSULTA COMPLETA ESPOCRM - VERIFICAÇÃO DE CAMPOS ===\n\n";

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

// IDs dos registros mais recentes que criamos (dos logs)
$recent_lead_ids = [
    '68faabf4af527df81', // Lead mais recente
    '68faaafbf1fe47ec5', // Lead anterior
    '68faa9358c1a66fb7'  // Lead anterior
];

$recent_opportunity_ids = [
    '68faabf4d00f16369', // Oportunidade mais recente
    '68faaafc1bec4f144', // Oportunidade anterior
    '68faa935b02ebc26b'  // Oportunidade anterior
];

echo "1. CONSULTANDO LEADS ESPECÍFICOS:\n";
foreach ($recent_lead_ids as $index => $lead_id) {
    echo "\n--- LEAD " . ($index + 1) . " (ID: $lead_id) ---\n";
    
    $lead_url = $espocrm_url . '/api/v1/Lead/' . $lead_id;
    $lead_result = makeRequest($lead_url, $headers);
    
    echo "HTTP Code: " . $lead_result['http_code'] . "\n";
    if ($lead_result['error']) {
        echo "cURL Error: " . $lead_result['error'] . "\n";
    }
    
    if ($lead_result['http_code'] === 200) {
        $lead_data = json_decode($lead_result['response'], true);
        if ($lead_data) {
            echo "✅ LEAD ENCONTRADO:\n";
            echo "  ID: " . ($lead_data['id'] ?? 'N/A') . "\n";
            echo "  Nome: " . ($lead_data['name'] ?? 'N/A') . "\n";
            echo "  First Name: " . ($lead_data['firstName'] ?? 'N/A') . "\n";
            echo "  Last Name: " . ($lead_data['lastName'] ?? 'N/A') . "\n";
            echo "  Email: " . ($lead_data['emailAddress'] ?? 'N/A') . "\n";
            echo "  Phone: " . ($lead_data['phoneNumber'] ?? 'N/A') . "\n";
            echo "  Source: " . ($lead_data['source'] ?? 'N/A') . "\n";
            echo "  Status: " . ($lead_data['status'] ?? 'N/A') . "\n";
            echo "  Description: " . ($lead_data['description'] ?? 'N/A') . "\n";
            echo "  Created: " . ($lead_data['createdAt'] ?? 'N/A') . "\n";
            echo "  Created By: " . ($lead_data['createdByName'] ?? 'N/A') . "\n";
            echo "  Modified: " . ($lead_data['modifiedAt'] ?? 'N/A') . "\n";
            
            // Verificar campos específicos que enviamos
            echo "\n  📋 CAMPOS ENVIADOS:\n";
            echo "    firstName: " . ($lead_data['firstName'] ?? 'N/A') . " (esperado: Nome do usuário)\n";
            echo "    lastName: " . ($lead_data['lastName'] ?? 'N/A') . " (esperado: vazio)\n";
            echo "    emailAddress: " . ($lead_data['emailAddress'] ?? 'N/A') . " (esperado: Email do usuário)\n";
            echo "    source: " . ($lead_data['source'] ?? 'N/A') . " (esperado: Site)\n";
            echo "    description: " . ($lead_data['description'] ?? 'N/A') . " (esperado: Lead enviado do ambiente de desenvolvimento - API V2)\n";
        } else {
            echo "❌ Erro ao decodificar dados do lead\n";
        }
    } else {
        echo "❌ LEAD NÃO ENCONTRADO (HTTP $lead_result[http_code])\n";
        echo "Response: " . $lead_result['response'] . "\n";
    }
}

echo "\n\n2. CONSULTANDO OPORTUNIDADES ESPECÍFICAS:\n";
foreach ($recent_opportunity_ids as $index => $opportunity_id) {
    echo "\n--- OPORTUNIDADE " . ($index + 1) . " (ID: $opportunity_id) ---\n";
    
    $opportunity_url = $espocrm_url . '/api/v1/Opportunity/' . $opportunity_id;
    $opportunity_result = makeRequest($opportunity_url, $headers);
    
    echo "HTTP Code: " . $opportunity_result['http_code'] . "\n";
    if ($opportunity_result['error']) {
        echo "cURL Error: " . $opportunity_result['error'] . "\n";
    }
    
    if ($opportunity_result['http_code'] === 200) {
        $opportunity_data = json_decode($opportunity_result['response'], true);
        if ($opportunity_data) {
            echo "✅ OPORTUNIDADE ENCONTRADA:\n";
            echo "  ID: " . ($opportunity_data['id'] ?? 'N/A') . "\n";
            echo "  Nome: " . ($opportunity_data['name'] ?? 'N/A') . "\n";
            echo "  Lead ID: " . ($opportunity_data['leadId'] ?? 'N/A') . "\n";
            echo "  Stage: " . ($opportunity_data['stage'] ?? 'N/A') . "\n";
            echo "  Amount: " . ($opportunity_data['amount'] ?? 'N/A') . "\n";
            echo "  Probability: " . ($opportunity_data['probability'] ?? 'N/A') . "\n";
            echo "  Lead Source: " . ($opportunity_data['leadSource'] ?? 'N/A') . "\n";
            echo "  Description: " . ($opportunity_data['description'] ?? 'N/A') . "\n";
            echo "  Created: " . ($opportunity_data['createdAt'] ?? 'N/A') . "\n";
            echo "  Created By: " . ($opportunity_data['createdByName'] ?? 'N/A') . "\n";
            echo "  Modified: " . ($opportunity_data['modifiedAt'] ?? 'N/A') . "\n";
            
            // Verificar campos específicos que enviamos
            echo "\n  📋 CAMPOS ENVIADOS:\n";
            echo "    name: " . ($opportunity_data['name'] ?? 'N/A') . " (esperado: Nome do usuário)\n";
            echo "    leadId: " . ($opportunity_data['leadId'] ?? 'N/A') . " (esperado: ID do lead relacionado)\n";
            echo "    stage: " . ($opportunity_data['stage'] ?? 'N/A') . " (esperado: Novo Sem Contato)\n";
            echo "    amount: " . ($opportunity_data['amount'] ?? 'N/A') . " (esperado: 0)\n";
            echo "    probability: " . ($opportunity_data['probability'] ?? 'N/A') . " (esperado: 10)\n";
            echo "    leadSource: " . ($opportunity_data['leadSource'] ?? 'N/A') . " (esperado: Site)\n";
            echo "    description: " . ($opportunity_data['description'] ?? 'N/A') . " (esperado: Oportunidade criada no ambiente de desenvolvimento - API V2)\n";
        } else {
            echo "❌ Erro ao decodificar dados da oportunidade\n";
        }
    } else {
        echo "❌ OPORTUNIDADE NÃO ENCONTRADA (HTTP $opportunity_result[http_code])\n";
        echo "Response: " . $opportunity_result['response'] . "\n";
    }
}

echo "\n\n3. CONSULTANDO TODOS OS LEADS RECENTES (últimos 10):\n";
$all_leads_url = $espocrm_url . '/api/v1/Lead?maxSize=10&sortBy=createdAt&asc=false';
$all_leads_result = makeRequest($all_leads_url, $headers);

echo "HTTP Code: " . $all_leads_result['http_code'] . "\n";
if ($all_leads_result['error']) {
    echo "cURL Error: " . $all_leads_result['error'] . "\n";
}

if ($all_leads_result['http_code'] === 200) {
    $all_leads_data = json_decode($all_leads_result['response'], true);
    if ($all_leads_data && isset($all_leads_data['list'])) {
        echo "Total de leads encontrados: " . count($all_leads_data['list']) . "\n\n";
        
        foreach ($all_leads_data['list'] as $index => $lead) {
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
        echo "❌ Erro ao decodificar lista de leads\n";
    }
} else {
    echo "❌ Erro ao consultar leads (HTTP $all_leads_result[http_code])\n";
    echo "Response: " . $all_leads_result['response'] . "\n";
}

echo "\n\n4. CONSULTANDO TODAS AS OPORTUNIDADES RECENTES (últimas 10):\n";
$all_opportunities_url = $espocrm_url . '/api/v1/Opportunity?maxSize=10&sortBy=createdAt&asc=false';
$all_opportunities_result = makeRequest($all_opportunities_url, $headers);

echo "HTTP Code: " . $all_opportunities_result['http_code'] . "\n";
if ($all_opportunities_result['error']) {
    echo "cURL Error: " . $all_opportunities_result['error'] . "\n";
}

if ($all_opportunities_result['http_code'] === 200) {
    $all_opportunities_data = json_decode($all_opportunities_result['response'], true);
    if ($all_opportunities_data && isset($all_opportunities_data['list'])) {
        echo "Total de oportunidades encontradas: " . count($all_opportunities_data['list']) . "\n\n";
        
        foreach ($all_opportunities_data['list'] as $index => $opportunity) {
            echo "Oportunidade " . ($index + 1) . ":\n";
            echo "  ID: " . ($opportunity['id'] ?? 'N/A') . "\n";
            echo "  Nome: " . ($opportunity['name'] ?? 'N/A') . "\n";
            echo "  Stage: " . ($opportunity['stage'] ?? 'N/A') . "\n";
            echo "  Lead Source: " . ($opportunity['leadSource'] ?? 'N/A') . "\n";
            echo "  Lead ID: " . ($opportunity['leadId'] ?? 'N/A') . "\n";
            echo "  Created: " . ($opportunity['createdAt'] ?? 'N/A') . "\n";
            echo "  Created By: " . ($opportunity['createdByName'] ?? 'N/A') . "\n";
            echo "\n";
        }
    } else {
        echo "❌ Erro ao decodificar lista de oportunidades\n";
    }
} else {
    echo "❌ Erro ao consultar oportunidades (HTTP $all_opportunities_result[http_code])\n";
    echo "Response: " . $all_opportunities_result['response'] . "\n";
}

echo "\n=== FIM DA CONSULTA COMPLETA ===\n";
?>
