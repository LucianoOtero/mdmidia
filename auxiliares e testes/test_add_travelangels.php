<?php
echo "=== TESTE DO ADD_TRAVELANGELS.PHP ===\n\n";

// Dados de teste para o endpoint add_travelangels.php
$testData = [
    'nome' => 'João Silva Teste',
    'email' => 'joao.teste@email.com',
    'telefone' => '11987654321',
    'cep' => '01234-567',
    'endereco' => 'Rua das Flores, 123',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'veiculo' => 'Honda Civic',
    'ano' => '2020',
    'placa' => 'ABC-1234',
    'cpf' => '123.456.789-00',
    'marca' => 'Honda',
    'gclid' => 'test_gclid_12345'
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
    // Tentar decodificar JSON
    $decodedResponse = json_decode($response, true);
    if ($decodedResponse) {
        echo "   JSON Response:\n";
        foreach ($decodedResponse as $key => $value) {
            echo "      $key: $value\n";
        }
    } else {
        echo "   Raw Response: " . substr($response, 0, 500) . "\n";
    }
} else {
    echo "   ❌ Nenhuma resposta recebida\n";
}

echo "\n" . str_repeat("=", 50) . "\n";

// Análise do resultado
if ($httpCode == 200) {
    echo "✅ SUCESSO! Lead criado com sucesso\n";
    echo "🎯 Verifique se a oportunidade foi criada no EspoCRM\n";
} elseif ($httpCode == 400) {
    echo "⚠️ ERRO 400: Dados inválidos\n";
    echo "🔍 Verifique se todos os campos obrigatórios foram enviados\n";
} elseif ($httpCode == 409) {
    echo "⚠️ ERRO 409: Lead duplicado\n";
    echo "🔍 Lead já existe no sistema\n";
} elseif ($httpCode == 500) {
    echo "❌ ERRO 500: Erro interno do servidor\n";
    echo "🔍 Verifique os logs do servidor\n";
} else {
    echo "❌ ERRO $httpCode: Resposta inesperada\n";
    echo "🔍 Verifique os logs do servidor\n";
}

echo "\n📋 PRÓXIMOS PASSOS:\n";
echo "1. Verificar logs do add_travelangels.php\n";
echo "2. Verificar se o lead foi criado no EspoCRM\n";
echo "3. Verificar se a oportunidade foi criada\n";
echo "4. Verificar logs do workflow\n";

echo "\n✅ TESTE CONCLUÍDO!\n";
