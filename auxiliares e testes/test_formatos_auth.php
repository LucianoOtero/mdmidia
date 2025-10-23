<?php
// Teste de diferentes formatos de autenticação

echo "=== TESTE FORMATOS DE AUTENTICAÇÃO ===\n\n";

$apiUrl = 'https://flyingdonkeys.com.br';
$apiKey = '82d5f667f3a65a9a43341a0705be2b0c';

echo "🔗 URL: $apiUrl\n";
echo "🔑 API Key: " . substr($apiKey, 0, 8) . "...\n\n";

// Teste 1: Bearer Token
echo "🔐 TESTE 1: BEARER TOKEN\n";
echo "========================\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Lead');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "📄 Response: " . substr($response, 0, 200) . "...\n";
echo ($httpCode === 200 ? "✅ Bearer Token funcionando!" : "❌ Bearer Token falhou") . "\n\n";

// Teste 2: API Key direta
echo "🔑 TESTE 2: API KEY DIRETA\n";
echo "==========================\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Lead');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $apiKey,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "📄 Response: " . substr($response, 0, 200) . "...\n";
echo ($httpCode === 200 ? "✅ API Key direta funcionando!" : "❌ API Key direta falhou") . "\n\n";

// Teste 3: X-API-Key header
echo "🔐 TESTE 3: X-API-KEY HEADER\n";
echo "============================\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Lead');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "📄 Response: " . substr($response, 0, 200) . "...\n";
echo ($httpCode === 200 ? "✅ X-API-Key funcionando!" : "❌ X-API-Key falhou") . "\n\n";

// Teste 4: Basic Auth
echo "🔐 TESTE 4: BASIC AUTH\n";
echo "=====================\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Lead');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode($apiKey . ':'),
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "📄 Response: " . substr($response, 0, 200) . "...\n";
echo ($httpCode === 200 ? "✅ Basic Auth funcionando!" : "❌ Basic Auth falhou") . "\n\n";

echo "=== RESUMO DOS TESTES ===\n";
echo "1. Bearer Token: " . ($httpCode === 200 ? "✅" : "❌") . "\n";
echo "2. API Key direta: " . ($httpCode === 200 ? "✅" : "❌") . "\n";
echo "3. X-API-Key: " . ($httpCode === 200 ? "✅" : "❌") . "\n";
echo "4. Basic Auth: " . ($httpCode === 200 ? "✅" : "❌") . "\n\n";

echo "✅ TESTE DE FORMATOS CONCLUÍDO!\n";
?>
