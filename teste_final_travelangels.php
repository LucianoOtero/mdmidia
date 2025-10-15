<?php
echo "=== TESTE FINAL DO ADD_TRAVELANGELS.PHP ===\n\n";

// Dados de teste com nome único
$testData = [
    'nome' => 'FINAL TEST SILVA',
    'email' => 'final.test@email.com',
    'telefone' => '11999888777',
    'cep' => '01234-567',
    'endereco' => 'Rua Final, 789',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'veiculo' => 'Honda Civic',
    'ano' => '2022',
    'placa' => 'FINAL-123',
    'cpf' => '111.222.333-44',
    'marca' => 'Honda',
    'gclid' => 'final_test_99999'
];

echo "📋 Dados de teste:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

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
            if (strpos($firstName, 'FINAL TEST') !== false || 
                strpos($email, 'final.test') !== false) {
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

echo "\n✅ TESTE FINAL CONCLUÍDO!\n";
?>
