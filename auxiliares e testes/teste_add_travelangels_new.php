<?php
/**
 * TESTE DO ENDPOINT add_travelangels_new.php NO BPSEGUROSIMEDIATO
 * 
 * Teste da versão corrigida com leadSource na oportunidade
 * 
 * Autor: Sistema de Testes
 * Data: 2025-10-22
 */

echo "=== TESTE add_travelangels_new.php CORRIGIDO ===\n";
echo "Servidor: bpsegurosimediato.com.br\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
echo "===============================================\n\n";

// Dados de teste com estrutura do add_travelangels
$testData = [
    'nome' => 'TESTE CORREÇÃO - ' . date('Y-m-d H:i:s'),
    'telefone' => '11999887766',
    'email' => 'teste.correcao@exemplo.com',
    'cep' => '01234567',
    'cpf' => '12345678901',
    'marca' => 'Honda',
    'placa' => 'ABC1234',
    'ano' => '2020',
    'gclid' => 'test_correcao_' . time(),
    'endereco' => 'Rua Teste, 123',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'veiculo' => 'Civic'
];

echo "📋 DADOS DE TESTE:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

// Endpoint
$endpoint = 'https://bpsegurosimediato.com.br/add_travelangels_new.php';

echo "🌐 ENDPOINT: $endpoint\n";
echo "📤 Enviando dados...\n\n";

// Função para fazer a requisição
function testEndpoint($url, $data) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: Teste-Correcao-v1.0'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $startTime = microtime(true);
    $response = curl_exec($ch);
    $endTime = microtime(true);
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $info = curl_getinfo($ch);
    
    curl_close($ch);
    
    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
        'info' => $info,
        'execution_time' => round(($endTime - $startTime) * 1000, 2)
    ];
}

// Executar o teste
$result = testEndpoint($endpoint, $testData);

echo "📊 RESULTADO:\n";
echo "=============\n";

if ($result['success']) {
    echo "✅ STATUS: SUCESSO\n";
    echo "📡 HTTP Code: " . $result['http_code'] . "\n";
} else {
    echo "❌ STATUS: FALHA\n";
    echo "📡 HTTP Code: " . $result['http_code'] . "\n";
    if ($result['error']) {
        echo "🚨 Erro cURL: " . $result['error'] . "\n";
    }
}

echo "⏱️  Tempo: " . $result['execution_time'] . "ms\n\n";

echo "📥 RESPOSTA:\n";
echo "============\n";

if ($result['response']) {
    $jsonResponse = json_decode($result['response'], true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ Resposta JSON válida:\n";
        echo json_encode($jsonResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        
        if (isset($jsonResponse['status'])) {
            echo "\n🎯 ANÁLISE:\n";
            echo "   - Status: " . $jsonResponse['status'] . "\n";
            
            if (isset($jsonResponse['message'])) {
                echo "   - Mensagem: " . $jsonResponse['message'] . "\n";
            }
            
            if (isset($jsonResponse['leadIdTravelAngels'])) {
                echo "   - Lead TravelAngels: " . $jsonResponse['leadIdTravelAngels'] . "\n";
            }
            
            if (isset($jsonResponse['leadIdFlyingDonkeys'])) {
                echo "   - Lead FlyingDonkeys: " . $jsonResponse['leadIdFlyingDonkeys'] . "\n";
            }
        }
    } else {
        echo "⚠️  Resposta não é JSON válido:\n";
        echo "Erro JSON: " . json_last_error_msg() . "\n";
        echo "Resposta bruta:\n";
        echo $result['response'] . "\n";
    }
} else {
    echo "❌ Nenhuma resposta recebida\n";
}

echo "\n🔍 VERIFICAÇÕES:\n";
echo "================\n";
echo "1. Logs no servidor: logs_travelangels.txt\n";
echo "2. TravelAngels: https://travelangels.com.br\n";
echo "3. FlyingDonkeys: https://flyingdonkeys.com.br\n";
echo "4. Buscar por email: " . $testData['email'] . "\n";
echo "5. Verificar campo leadSource na oportunidade\n\n";

echo "=== FIM DO TESTE ===\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
?>
