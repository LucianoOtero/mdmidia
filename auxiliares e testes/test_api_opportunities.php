<?php
// Teste simples para verificar a API de oportunidades

function testApiOpportunities($leadId, $system)
{
    $apiUrl = $system === 'TravelAngels' ? 'https://travelangels.com.br' : 'https://flyingdonkeys.com.br';
    $apiKey = '7a6c08d438ee131971f561fd836b5e15';

    echo "🔍 Testando API: $apiUrl\n";
    echo "🔑 API Key: " . substr($apiKey, 0, 8) . "...\n";
    echo "🆔 Lead ID: $leadId\n";

    $url = $apiUrl . '/api/v1/Opportunity?where[0][type]=equals&where[0][attribute]=leadId&where[0][value]=' . $leadId;
    echo "🌐 URL: $url\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    echo "📊 HTTP Code: $httpCode\n";
    echo "❌ cURL Error: " . ($curlError ?: 'Nenhum') . "\n";
    echo "📄 Response: " . substr($response, 0, 200) . "...\n";

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['list'])) {
            echo "✅ Oportunidades encontradas: " . count($data['list']) . "\n";
            foreach ($data['list'] as $opp) {
                echo "   - ID: " . $opp['id'] . " | Nome: " . $opp['name'] . "\n";
            }
        } else {
            echo "⚠️ Resposta não é JSON válido ou sem 'list'\n";
        }
    } else {
        echo "❌ Erro HTTP: $httpCode\n";
    }

    echo "---\n";
}

// Testar com um lead ID conhecido
$leadId = '68efb564e3f6a25f2'; // Lead do teste 1

echo "=== TESTE API OPORTUNIDADES ===\n\n";

testApiOpportunities($leadId, 'TravelAngels');
testApiOpportunities($leadId, 'FlyingDonkeys');

echo "✅ TESTE CONCLUÍDO!\n";
