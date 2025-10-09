<?php
// Arquivo de teste para o endpoint add_leadsgo.php
// Este arquivo simula o envio de dados do leadsgo.online

// Dados de exemplo que o leadsgo.online enviaria
$testData = [
    'data' => [
        'NOME' => 'João Silva',
        'DDD-CELULAR' => '011',
        'CELULAR' => '987654321',
        'Email' => 'joao.silva@email.com',
        'CEP' => '01234-567',
        'CPF' => '123.456.789-00',
        'MARCA' => 'Toyota',
        'PLACA' => 'ABC1234',
        'ANO' => '2020',
        'GCLID_FLD' => 'test_gclid_123'
    ],
    'd' => '2024-01-15 10:30:00',
    'name' => 'leadsgo.online'
];

// Simula o envio via cURL
$url = 'https://mdmidia.com.br/add_leadsgo.php';
$jsonData = json_encode($testData);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status HTTP: " . $httpCode . "\n";
echo "Resposta: " . $response . "\n";
?>
