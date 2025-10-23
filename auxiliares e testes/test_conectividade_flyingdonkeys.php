<?php
// Teste de conectividade com FlyingDonkeys

echo "=== TESTE DE CONECTIVIDADE FLYINGDONKEYS ===\n\n";

$apiUrl = 'https://flyingdonkeys.com.br';
$apiKey = '82d5f667f3a65a9a43341a0705be2b0c';

echo "🔗 URL: $apiUrl\n";
echo "🔑 API Key: " . substr($apiKey, 0, 8) . "...\n\n";

// Teste 1: Verificar se o servidor está online
echo "📡 TESTE 1: VERIFICAR SERVIDOR ONLINE\n";
echo "=====================================\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request only

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "❌ cURL Error: " . ($curlError ?: 'Nenhum') . "\n";

if ($httpCode === 200) {
    echo "✅ Servidor online!\n";
} else {
    echo "❌ Servidor com problema!\n";
}
echo "\n";

// Teste 2: Verificar API básica
echo "🔌 TESTE 2: VERIFICAR API BÁSICA\n";
echo "================================\n";

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
$curlError = curl_error($ch);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "❌ cURL Error: " . ($curlError ?: 'Nenhum') . "\n";
echo "📄 Response: " . substr($response, 0, 200) . "...\n";

if ($httpCode === 200) {
    echo "✅ API funcionando!\n";
} elseif ($httpCode === 401) {
    echo "❌ Erro de autenticação (API Key inválida)\n";
} elseif ($httpCode === 500) {
    echo "❌ Erro interno do servidor\n";
} else {
    echo "❌ Erro HTTP: $httpCode\n";
}
echo "\n";

// Teste 3: Verificar API de Oportunidades
echo "🎯 TESTE 3: VERIFICAR API OPORTUNIDADES\n";
echo "======================================\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Opportunity');
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
$curlError = curl_error($ch);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "❌ cURL Error: " . ($curlError ?: 'Nenhum') . "\n";
echo "📄 Response: " . substr($response, 0, 200) . "...\n";

if ($httpCode === 200) {
    echo "✅ API de Oportunidades funcionando!\n";
} elseif ($httpCode === 401) {
    echo "❌ Erro de autenticação (API Key inválida)\n";
} elseif ($httpCode === 500) {
    echo "❌ Erro interno do servidor\n";
} else {
    echo "❌ Erro HTTP: $httpCode\n";
}
echo "\n";

// Teste 4: Teste de criação de lead simples
echo "🧪 TESTE 4: TESTE DE CRIAÇÃO DE LEAD\n";
echo "====================================\n";

$testPayload = [
    'firstName' => 'Teste Conectividade V8',
    'emailAddress' => 'teste.conectividade.v8@teste.com',
    'cCelular' => '11999887766',
    'source' => 'Teste'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Lead');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Accept: application/json',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testPayload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "📊 HTTP Code: $httpCode\n";
echo "❌ cURL Error: " . ($curlError ?: 'Nenhum') . "\n";
echo "📄 Response: " . substr($response, 0, 300) . "...\n";

if ($httpCode === 200 || $httpCode === 201) {
    echo "✅ Criação de Lead funcionando!\n";
} elseif ($httpCode === 401) {
    echo "❌ Erro de autenticação (API Key inválida)\n";
} elseif ($httpCode === 500) {
    echo "❌ Erro interno do servidor\n";
} else {
    echo "❌ Erro HTTP: $httpCode\n";
}
echo "\n";

echo "=== RESUMO DOS TESTES ===\n";
echo "1. Servidor online: " . ($httpCode === 200 ? "✅" : "❌") . "\n";
echo "2. API básica: " . ($httpCode === 200 ? "✅" : "❌") . "\n";
echo "3. API Oportunidades: " . ($httpCode === 200 ? "✅" : "❌") . "\n";
echo "4. Criação Lead: " . ($httpCode === 200 || $httpCode === 201 ? "✅" : "❌") . "\n\n";

echo "✅ TESTE DE CONECTIVIDADE CONCLUÍDO!\n";
