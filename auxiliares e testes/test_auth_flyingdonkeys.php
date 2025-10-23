<?php
require_once('class.php');

echo "=== TESTE DE AUTENTICAÇÃO - FLYINGDONKEYS ===\n\n";

// Teste 1: Verificar se a API está respondendo
echo "1. Testando conectividade básica...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
if ($error) {
    echo "   Erro cURL: $error\n";
}
echo "   Resposta: " . substr($response, 0, 200) . "\n\n";

// Teste 2: Usando a classe EspoApiClient
echo "2. Testando com EspoApiClient...\n";
$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

try {
    $result = $client->request('GET', 'v1');
    echo "   ✅ Sucesso com EspoApiClient\n";
    echo "   Resposta: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "   ❌ Erro com EspoApiClient: " . $e->getMessage() . "\n";
}

echo "\n3. Testando diferentes métodos de autenticação...\n";

// Teste com header diferente
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/Workflow');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer 7a6c08d438ee131971f561fd836b5e15',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   Teste Bearer Token - HTTP: $httpCode\n";
echo "   Resposta: " . substr($response, 0, 200) . "\n\n";

// Teste com header X-Api-Key diferente
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/Workflow');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Api-Key: 7a6c08d438ee131971f561fd836b5e15',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   Teste X-Api-Key - HTTP: $httpCode\n";
echo "   Resposta: " . substr($response, 0, 200) . "\n\n";

echo "=== FIM DO TESTE ===\n";





