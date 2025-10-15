<?php
echo "=== EXECUTANDO ADD_TRAVELANGELS.PHP NO SERVIDOR ===\n\n";

// Simular os dados que o add_travelangels.php espera (formato antigo)
$testData = [
    'data' => [
        'NOME' => 'TESTE CACHE LIMPO',
        'Email' => 'teste.cache.limpo@email.com',
        'DDD-CELULAR' => '11',
        'CELULAR' => '999888777',
        'CEP' => '01234-567',
        'CPF' => '222.333.444-55',
        'MARCA' => 'Honda',
        'PLACA' => 'SERV-123',
        'ANO' => '2022',
        'GCLID_FLD' => 'test_servidor_12345'
    ],
    'd' => date('Y-m-d H:i:s'),
    'name' => 'mdmidia.com.br'
];

echo "📋 Dados no formato esperado pelo servidor:\n";
echo "   NOME: " . $testData['data']['NOME'] . "\n";
echo "   Email: " . $testData['data']['Email'] . "\n";
echo "   CELULAR: " . $testData['data']['DDD-CELULAR'] . $testData['data']['CELULAR'] . "\n";
echo "   CEP: " . $testData['data']['CEP'] . "\n";
echo "   CPF: " . $testData['data']['CPF'] . "\n";
echo "   MARCA: " . $testData['data']['MARCA'] . "\n";
echo "   PLACA: " . $testData['data']['PLACA'] . "\n";
echo "   ANO: " . $testData['data']['ANO'] . "\n";
echo "   GCLID: " . $testData['data']['GCLID_FLD'] . "\n\n";

// URL do endpoint
$url = 'https://mdmidia.com.br/add_travelangels.php';

echo "🌐 Enviando dados para: $url\n\n";

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($testData))
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

// Executar requisição
echo "⏳ Executando requisição...\n";
$startTime = microtime(true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$totalTime = microtime(true) - $startTime;

curl_close($ch);

// Exibir resultados
echo "📊 RESULTADOS:\n";
echo "   HTTP Code: $httpCode\n";
echo "   Tempo de resposta: " . round($totalTime, 2) . " segundos\n";

if ($error) {
    echo "   ❌ Erro cURL: $error\n";
} else {
    echo "   ✅ Requisição executada sem erros cURL\n";
}

echo "\n📝 Resposta do servidor:\n";
if ($response) {
    echo "   Tamanho: " . strlen($response) . " bytes\n";
    echo "   Conteúdo: $response\n";
} else {
    echo "   ❌ Nenhuma resposta recebida\n";
}

echo "\n" . str_repeat("=", 50) . "\n";

// Aguardar e verificar se o lead foi criado
echo "🔍 Aguardando 5 segundos para verificar se o lead foi criado...\n";
sleep(5);

// Verificar se o lead foi criado
$apiUrl = 'https://flyingdonkeys.com.br/api/v1/Lead';
$apiKey = '7a6c08d438ee131971f561fd836b5e15';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '?maxSize=5');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: ' . $apiKey]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 && $response) {
    $data = json_decode($response, true);

    if (isset($data['list']) && count($data['list']) > 0) {
        echo "\n📋 ÚLTIMOS 5 LEADS:\n";

        $found = false;
        foreach ($data['list'] as $lead) {
            $createdAt = $lead['createdAt'] ?? 'N/A';
            $firstName = $lead['firstName'] ?? 'N/A';
            $source = $lead['source'] ?? 'N/A';
            $email = $lead['emailAddress'] ?? 'N/A';

            echo "   👤 $firstName\n";
            echo "      📅 Criado: $createdAt\n";
            echo "      🏷️ Source: $source\n";
            echo "      📧 Email: $email\n";
            echo "      🆔 ID: " . $lead['id'] . "\n\n";

            // Verificar se é o nosso lead
            if (
                strpos($firstName, 'TESTE CACHE LIMPO') !== false ||
                strpos($email, 'teste.cache.limpo') !== false
            ) {
                $found = true;
                echo "   ✅ LEAD ENCONTRADO!\n";
            }
        }

        if (!$found) {
            echo "❌ LEAD NÃO ENCONTRADO!\n";
            echo "🔍 O endpoint ainda está com problema.\n";
        }
    }
}

echo "\n✅ TESTE NO SERVIDOR CONCLUÍDO!\n";
