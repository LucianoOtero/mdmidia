<?php
// Teste do endpoint add_flyingdonkeys.php

echo "=== TESTE ENDPOINT ADD_FLYINGDONKEYS ===\n\n";

// URL do endpoint
$url = 'https://mdmidia.com.br/add_flyingdonkeys.php';

// Dados de teste
$testData = [
    'nome' => 'Teste FlyingDonkeys V13',
    'email' => 'teste.flyingdonkeys.v13@teste.com',
    'telefone' => '11999887766',
    'cpf' => '111.222.333-44',
    'marca' => 'Honda',
    'placa' => 'ABC1234',
    'ano' => '2020',
    'cep' => '01234-567',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'endereco' => 'Rua Teste, 123',
    'gclid' => 'test_gclid_flyingdonkeys'
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
echo "📋 Verificando logs_flyingdonkeys.txt...\n";

// Tentar verificar os logs (se possível)
$logUrl = 'https://mdmidia.com.br/logs_flyingdonkeys.txt';
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
    $recentLines = array_slice($logLines, -10); // Últimas 10 linhas
    foreach ($recentLines as $line) {
        if (trim($line)) {
            echo "   $line\n";
        }
    }
} else {
    echo "⚠️ Não foi possível acessar os logs (HTTP: $logHttpCode)\n";
}

echo "\n=== RESUMO DO TESTE ===\n";
if ($httpCode === 200 && $responseData && $responseData['status'] === 'success') {
    echo "✅ TESTE BEM-SUCEDIDO!\n";
    echo "   - Endpoint respondeu corretamente\n";
    echo "   - Lead foi processado com sucesso\n";
    echo "   - Status: " . $responseData['status'] . "\n";
    echo "   - Mensagem: " . $responseData['message'] . "\n";
} else {
    echo "❌ TESTE FALHOU!\n";
    echo "   - HTTP Code: $httpCode\n";
    echo "   - Resposta: $response\n";
}

echo "\n✅ TESTE CONCLUÍDO!\n";
