<?php
// Teste dos métodos GET e POST
// URL: https://mdmidia.com.br/teste_get_post.php

echo "=== TESTE GET E POST ===\n\n";

$baseUrl = 'https://mdmidia.com.br/add_leadsgo.php';

// Dados de teste
$testData = [
    'data' => [
        'NOME' => 'Teste GET/POST',
        'DDD-CELULAR' => '011',
        'CELULAR' => '987654321',
        'Email' => 'teste.getpost@email.com',
        'CEP' => '01234-567',
        'CPF' => '123.456.789-00',
        'MARCA' => 'Toyota',
        'PLACA' => 'ABC1234',
        'ANO' => '2020',
        'GCLID_FLD' => 'test_getpost_123'
    ],
    'd' => '2024-01-15 10:30:00',
    'name' => 'leadsgo.online'
];

// Teste 1: POST
echo "1. Testando POST:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Resposta: $response\n\n";

// Teste 2: GET
echo "2. Testando GET:\n";
$queryString = http_build_query($testData);
$getUrl = $baseUrl . '?' . $queryString;
echo "URL: $getUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $getUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Resposta: $response\n\n";

// Teste 3: GET simplificado
echo "3. Testando GET simplificado:\n";
$simpleData = [
    'data' => json_encode($testData['data']),
    'd' => $testData['d'],
    'name' => $testData['name']
];
$queryString = http_build_query($simpleData);
$getUrl = $baseUrl . '?' . $queryString;
echo "URL: $getUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $getUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Resposta: $response\n\n";

echo "=== TESTE CONCLUÍDO ===\n";
?>
