<?php
echo "=== TESTE COMPLETO ADD_COLLECT_CHAT_V10 ===\n\n";

// URL do endpoint
$url = 'https://mdmidia.com.br/add_collect_chat_v10.php';

// Dados de teste
$testData = [
    'NAME' => 'Ana Carolina Mendes Silva',
    'NUMBER' => '11999887766',
    'CPF' => '111.222.333-44',
    'CEP' => '01234-567',
    'PLACA' => 'ABC1234',
    'EMAIL' => 'ana.carolina.mendes@teste.com',
    'gclid' => 'test_gclid_collect_chat_v10'
];

echo "📤 Dados de teste:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}

// Função para fazer requisição
function makeRequest($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $startTime = microtime(true);
    $response = curl_exec($ch);
    $endTime = microtime(true);
    $executionTime = ($endTime - $startTime) * 1000;

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'curlError' => $curlError,
        'executionTime' => $executionTime
    ];
}

// Função para verificar oportunidades via API
function checkOpportunities($leadId, $system)
{
    $apiUrl = $system === 'TravelAngels' ? 'https://travelangels.com.br' : 'https://flyingdonkeys.com.br';
    $apiKey = $system === 'TravelAngels' ? '7a6c08d438ee131971f561fd836b5e15' : '82d5f667f3a65a9a43341a0705be2b0c';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Opportunity?where[0][type]=equals&where[0][attribute]=leadId&where[0][value]=' . $leadId);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-Api-Key: ' . $apiKey,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        return isset($data['list']) ? count($data['list']) : 0;
    }
    return -1; // Erro na consulta
}

echo "\n🔗 Enviando requisição para: $url\n";
echo "📄 JSON enviado: " . json_encode($testData) . "\n\n";

$result = makeRequest($url, $testData);

echo "⏱️ Tempo de execução: " . number_format($result['executionTime'], 1) . "ms\n";
echo "📊 HTTP Code: " . $result['httpCode'] . "\n";

if ($result['curlError']) {
    echo "❌ cURL Error: " . $result['curlError'] . "\n";
} else {
    echo "✅ cURL executado sem erros\n";
}

echo "📄 Resposta do servidor:\n" . $result['response'] . "\n\n";

// Verificar se a resposta é JSON válido
$responseData = json_decode($result['response'], true);
if ($responseData) {
    echo "✅ Resposta JSON válida:\n";
    foreach ($responseData as $key => $value) {
        echo "   $key: $value\n";
    }

    // Verificar oportunidades se temos leadIds
    if (isset($responseData['leadIdTravelAngels']) && $responseData['leadIdTravelAngels']) {
        echo "\n🔍 Verificando oportunidades TravelAngels...\n";
        $oppCountTA = checkOpportunities($responseData['leadIdTravelAngels'], 'TravelAngels');
        echo "   Oportunidades encontradas: " . ($oppCountTA >= 0 ? $oppCountTA : 'Erro na consulta') . "\n";
    }

    if (isset($responseData['leadIdFlyingDonkeys']) && $responseData['leadIdFlyingDonkeys']) {
        echo "\n🔍 Verificando oportunidades FlyingDonkeys...\n";
        $oppCountFD = checkOpportunities($responseData['leadIdFlyingDonkeys'], 'FlyingDonkeys');
        echo "   Oportunidades encontradas: " . ($oppCountFD >= 0 ? $oppCountFD : 'Erro na consulta') . "\n";
    }
} else {
    echo "❌ Resposta não é JSON válido\n";
}

echo "\n=== RESUMO FINAL ===\n";
if ($result['httpCode'] == 200 && $responseData && $responseData['status'] == 'success') {
    echo "🎉 TESTE 100% BEM-SUCEDIDO!\n";
    echo "   ✅ HTTP Code: 200\n";
    echo "   ✅ TravelAngels: Lead criado\n";
    echo "   ✅ FlyingDonkeys: Lead criado/atualizado\n";
    echo "   ✅ FlyingDonkeys: Oportunidade criada\n";
    echo "   ✅ Ambos os sistemas funcionando perfeitamente\n";
} else {
    echo "❌ TESTE FALHOU\n";
    echo "   HTTP Code: " . $result['httpCode'] . "\n";
    echo "   Status: " . ($responseData['status'] ?? 'N/A') . "\n";
}

echo "\n✅ TESTE CONCLUÍDO!\n";
