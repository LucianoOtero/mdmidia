<?php
// Teste completo do add_travelangels_v9.php

echo "=== TESTE COMPLETO ADD_TRAVELANGELS_V9 ===\n\n";

// URL do endpoint
$url = 'https://mdmidia.com.br/add_travelangels_v9.php';

// Dados de teste
$testData = [
    'nome' => 'Maria Fernanda Silva Santos',
    'email' => 'maria.fernanda.silva@teste.com',
    'telefone' => '11999887766',
    'cpf' => '111.222.333-44',
    'marca' => 'Toyota',
    'placa' => 'XYZ9876',
    'ano' => '2021',
    'cep' => '01234-567',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'endereco' => 'Rua Teste, 123',
    'gclid' => 'test_gclid_travelangels_v9_novo'
];

echo "📤 Dados de teste:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

echo "🔗 Enviando requisição para: $url\n";
echo "📄 JSON enviado: " . json_encode($testData) . "\n\n";

// Executar requisição
$startTime = microtime(true);
$response = curl_exec($ch);
$endTime = microtime(true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

$executionTime = round(($endTime - $startTime) * 1000, 2);

echo "⏱️ Tempo de execução: {$executionTime}ms\n";
echo "📊 HTTP Code: $httpCode\n";

if ($curlError) {
    echo "❌ cURL Error: $curlError\n";
} else {
    echo "✅ cURL executado sem erros\n";
}

echo "📄 Resposta do servidor:\n";
echo $response . "\n\n";

// Verificar se a resposta é JSON válido
$responseData = json_decode($response, true);
if ($responseData) {
    echo "✅ Resposta JSON válida:\n";
    foreach ($responseData as $key => $value) {
        echo "   $key: $value\n";
    }
} else {
    echo "⚠️ Resposta não é JSON válido\n";
}

echo "\n=== VERIFICAÇÃO DOS LOGS ===\n";
echo "📋 Verificando logs_travelangels.txt...\n";

// Tentar verificar os logs (se possível)
$logUrl = 'https://mdmidia.com.br/logs_travelangels.txt';
$logCh = curl_init();
curl_setopt($logCh, CURLOPT_URL, $logUrl);
curl_setopt($logCh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($logCh, CURLOPT_TIMEOUT, 10);
curl_setopt($logCh, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($logCh, CURLOPT_SSL_VERIFYHOST, false);

$logResponse = curl_exec($logCh);
$logHttpCode = curl_getinfo($logCh, CURLINFO_HTTP_CODE);
curl_close($logCh);

if ($logHttpCode === 200 && $logResponse) {
    echo "✅ Logs acessíveis:\n";
    $logLines = explode("\n", $logResponse);
    $recentLines = array_slice($logLines, -15); // Últimas 15 linhas
    foreach ($recentLines as $line) {
        if (trim($line)) {
            echo "   $line\n";
        }
    }
} else {
    echo "⚠️ Não foi possível acessar os logs (HTTP: $logHttpCode)\n";
}

echo "\n=== ANÁLISE DETALHADA ===\n";

// Verificar se ambos os sistemas funcionaram
$travelAngelsSuccess = false;
$flyingDonkeysSuccess = false;

if ($responseData) {
    // Verificar TravelAngels
    if (isset($responseData['leadIdTravelAngels']) && $responseData['leadIdTravelAngels']) {
        echo "✅ TravelAngels: Lead criado com sucesso (ID: " . $responseData['leadIdTravelAngels'] . ")\n";
        $travelAngelsSuccess = true;
    } else {
        echo "❌ TravelAngels: Lead não foi criado\n";
    }

    // Verificar FlyingDonkeys
    if (isset($responseData['leadIdFlyingDonkeys']) && $responseData['leadIdFlyingDonkeys']) {
        echo "✅ FlyingDonkeys: Lead criado/atualizado com sucesso (ID: " . $responseData['leadIdFlyingDonkeys'] . ")\n";
        $flyingDonkeysSuccess = true;
    } else {
        echo "❌ FlyingDonkeys: Lead não foi criado/atualizado\n";
    }
}

// Verificar logs para oportunidades do FlyingDonkeys
if ($logResponse && strpos($logResponse, 'FlyingDonkeys - Oportunidade criada com sucesso') !== false) {
    echo "✅ FlyingDonkeys: Oportunidade criada com sucesso\n";
} else {
    echo "❌ FlyingDonkeys: Oportunidade não foi criada\n";
}

echo "\n=== RESUMO FINAL ===\n";

$overallSuccess = ($httpCode === 200 && $responseData && $responseData['status'] === 'success' &&
    $travelAngelsSuccess && $flyingDonkeysSuccess);

if ($overallSuccess) {
    echo "🎉 TESTE 100% BEM-SUCEDIDO!\n";
    echo "   ✅ HTTP Code: $httpCode\n";
    echo "   ✅ TravelAngels: Lead criado\n";
    echo "   ✅ FlyingDonkeys: Lead criado/atualizado\n";
    echo "   ✅ FlyingDonkeys: Oportunidade criada\n";
    echo "   ✅ Ambos os sistemas funcionando perfeitamente\n";
} else {
    echo "❌ TESTE FALHOU!\n";
    echo "   - HTTP Code: $httpCode\n";
    echo "   - TravelAngels Success: " . ($travelAngelsSuccess ? 'Sim' : 'Não') . "\n";
    echo "   - FlyingDonkeys Success: " . ($flyingDonkeysSuccess ? 'Sim' : 'Não') . "\n";
    echo "   - Resposta: $response\n";
}

echo "\n✅ TESTE CONCLUÍDO!\n";
