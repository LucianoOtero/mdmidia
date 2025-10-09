<?php
// Teste dos diferentes cenários de erro do endpoint
// URL: https://mdmidia.com.br/teste_erros_leadsgo.php

echo "=== TESTE DE CENÁRIOS DE ERRO ===\n\n";

$baseUrl = 'https://mdmidia.com.br/add_leadsgo.php';

// Cenário 1: GET (método incorreto)
echo "1. Testando GET (método incorreto):\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Resposta: $response\n\n";

// Cenário 2: POST sem dados
echo "2. Testando POST sem dados:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Resposta: $response\n\n";

// Cenário 3: POST com JSON inválido
echo "3. Testando POST com JSON inválido:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"invalid": json}');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Resposta: $response\n\n";

// Cenário 4: POST com Content-Type incorreto
echo "4. Testando POST com Content-Type incorreto:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"test": "data"}');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/plain']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status: $httpCode\n";
echo "Resposta: $response\n\n";

// Cenário 5: POST correto (deve funcionar)
echo "5. Testando POST correto:\n";
$testData = [
    'data' => [
        'NOME' => 'Teste Erro',
        'DDD-CELULAR' => '011',
        'CELULAR' => '987654321',
        'Email' => 'teste.erro@email.com',
        'CEP' => '01234-567',
        'CPF' => '123.456.789-00',
        'MARCA' => 'Toyota',
        'PLACA' => 'ABC1234',
        'ANO' => '2020',
        'GCLID_FLD' => 'test_erro_123'
    ],
    'd' => '2024-01-15 10:30:00',
    'name' => 'leadsgo.online'
];

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

echo "=== TESTE CONCLUÍDO ===\n";
?>
