<?php
// ============================================================================
// TESTE REAL COM CURL PARA ADD_LEADSGO_V11.PHP
// ============================================================================

echo "=== TESTE REAL COM CURL ===\n\n";

// Dados de teste
$testData = [
    'nome_segurado' => 'Maria Santos Oliveira',
    'email' => 'maria.santos@teste.com.br',
    'telefone_celular' => '11987654321',
    'cep' => '04567-890',
    'cpf_segurado' => '987.654.321-00',
    'marca' => 'Honda',
    'placa' => 'XYZ9876',
    'ano' => '2021',
    'modelo' => 'Civic',
    'endereco' => 'Avenida Paulista',
    'numero' => '1000',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'valor_veiculo' => '95000',
    'data_nascimento' => '1985-05-15',
    'estado_civil' => 'Casada',
    'sexo' => 'Feminino',
    'uso' => 'Particular',
    'pernoite' => 'Garagem',
    'seguradora_preferencia' => 'Bradesco Seguros',
    'valor_preferencia' => '2500',
    'modalidade_seguro' => 'Completo',
    'seguradora_apolice' => 'Itaú Seguros',
    'ci' => 'CI789012'
];

echo "📋 DADOS DE TESTE:\n";
echo "   Nome: " . $testData['nome_segurado'] . "\n";
echo "   Email: " . $testData['email'] . "\n";
echo "   Telefone: " . $testData['telefone_celular'] . "\n";
echo "   Veículo: " . $testData['marca'] . " " . $testData['modelo'] . " " . $testData['placa'] . "\n";
echo "   Seguradora Preferida: " . $testData['seguradora_preferencia'] . "\n\n";

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/mdmidia/add_leadsgo_v11.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: Teste-LeadsGo-v11'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

echo "🚀 ENVIANDO REQUISIÇÃO...\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
$curlInfo = curl_getinfo($ch);

curl_close($ch);

echo "📊 RESULTADOS:\n";
echo "   HTTP Code: " . $httpCode . "\n";
echo "   cURL Error: " . ($curlError ?: 'Nenhum') . "\n";
echo "   Content-Type: " . $curlInfo['content_type'] . "\n";
echo "   Total Time: " . $curlInfo['total_time'] . "s\n\n";

echo "📤 RESPOSTA COMPLETA:\n";
echo $response . "\n\n";

// Tentar decodificar JSON
$decodedResponse = json_decode($response, true);
if ($decodedResponse) {
    echo "📋 RESPOSTA DECODIFICADA:\n";
    echo "   Status: " . ($decodedResponse['status'] ?? 'N/A') . "\n";
    echo "   Message: " . ($decodedResponse['message'] ?? 'N/A') . "\n";
    echo "   Method: " . ($decodedResponse['method'] ?? 'N/A') . "\n";
    echo "   Content-Type: " . ($decodedResponse['content_type'] ?? 'N/A') . "\n\n";
    
    if (isset($decodedResponse['status']) && $decodedResponse['status'] === 'success') {
        echo "✅ TESTE BEM-SUCEDIDO!\n";
    } else {
        echo "❌ TESTE FALHOU!\n";
    }
} else {
    echo "❌ Resposta não é JSON válido\n";
}

echo "\n📁 VERIFICANDO LOGS...\n";
if (file_exists('logs_leadsgo.txt')) {
    $logs = file_get_contents('logs_leadsgo.txt');
    $lastLogs = substr($logs, -1000); // Últimos 1000 caracteres
    echo "📝 ÚLTIMOS LOGS:\n";
    echo $lastLogs . "\n";
} else {
    echo "❌ Arquivo de logs não encontrado\n";
}

echo "\n=== FIM DO TESTE COM CURL ===\n";




