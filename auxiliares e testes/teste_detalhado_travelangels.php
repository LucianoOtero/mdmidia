<?php
echo "=== TESTE DETALHADO DO ADD_TRAVELANGELS.PHP ===\n\n";

// Dados de teste com nome mais específico
$testData = [
    'nome' => 'TESTE DETALHADO SILVA',
    'email' => 'teste.detalhado@email.com',
    'telefone' => '11999888777',
    'cep' => '01234-567',
    'endereco' => 'Rua Teste, 456',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'veiculo' => 'Toyota Corolla',
    'ano' => '2021',
    'placa' => 'XYZ-9876',
    'cpf' => '987.654.321-00',
    'marca' => 'Toyota',
    'gclid' => 'test_detalhado_67890'
];

echo "📋 Dados de teste:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

// URL do endpoint
$url = 'https://mdmidia.com.br/add_travelangels.php';

echo "🌐 Enviando dados para: $url\n\n";

// Configurar cURL com mais detalhes
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
curl_setopt($ch, CURLOPT_VERBOSE, true);

// Executar requisição
echo "⏳ Executando requisição...\n";
$startTime = microtime(true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$totalTime = microtime(true) - $startTime;

// Informações detalhadas do cURL
$info = curl_getinfo($ch);
curl_close($ch);

// Exibir resultados detalhados
echo "📊 RESULTADOS DETALHADOS:\n";
echo "   HTTP Code: $httpCode\n";
echo "   Tempo de resposta: " . round($totalTime, 2) . " segundos\n";
echo "   Content-Type: " . ($info['content_type'] ?? 'N/A') . "\n";
echo "   Content-Length: " . ($info['download_content_length'] ?? 'N/A') . "\n";

if ($error) {
    echo "   ❌ Erro cURL: $error\n";
} else {
    echo "   ✅ Requisição executada sem erros cURL\n";
}

echo "\n📝 Resposta completa do servidor:\n";
if ($response) {
    echo "   Tamanho da resposta: " . strlen($response) . " bytes\n";
    echo "   Conteúdo:\n";
    echo "   " . str_repeat("-", 50) . "\n";
    echo "   $response\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    // Tentar decodificar JSON
    $decodedResponse = json_decode($response, true);
    if ($decodedResponse) {
        echo "\n   JSON Decodificado:\n";
        foreach ($decodedResponse as $key => $value) {
            echo "      $key: $value\n";
        }
    } else {
        echo "\n   ⚠️ Resposta não é JSON válido\n";
    }
} else {
    echo "   ❌ Nenhuma resposta recebida\n";
}

echo "\n" . str_repeat("=", 60) . "\n";

// Análise do resultado
if ($httpCode == 200) {
    echo "✅ HTTP 200 - Requisição bem-sucedida\n";
    if ($response && strpos($response, 'success') !== false) {
        echo "✅ Resposta indica sucesso\n";
    } else {
        echo "⚠️ Resposta não indica sucesso claramente\n";
    }
} else {
    echo "❌ HTTP $httpCode - Erro na requisição\n";
}

echo "\n🔍 AGORA VAMOS VERIFICAR SE O LEAD FOI CRIADO...\n";
echo "   Aguarde 5 segundos para o sistema processar...\n";
sleep(5);

// Verificar se o lead foi criado
$apiUrl = 'https://flyingdonkeys.com.br/api/v1/Lead';
$apiKey = '7a6c08d438ee131971f561fd836b5e15';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '?maxSize=10');
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
        echo "\n📋 VERIFICAÇÃO DOS ÚLTIMOS 10 LEADS:\n";
        
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
            if (strpos($firstName, 'TESTE DETALHADO') !== false || 
                strpos($email, 'teste.detalhado') !== false) {
                $found = true;
                echo "   ✅ LEAD ENCONTRADO!\n";
            }
        }
        
        if (!$found) {
            echo "❌ LEAD NÃO ENCONTRADO!\n";
            echo "🔍 O endpoint pode estar falhando silenciosamente.\n";
        }
    }
}

echo "\n✅ TESTE DETALHADO CONCLUÍDO!\n";
?>
