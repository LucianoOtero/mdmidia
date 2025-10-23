<?php
/**
 * Teste Comparativo - add_collect_chat.php vs add_collect_chat.php
 * Testa ambos os endpoints com dados diferentes e examina os logs
 */

echo "=== TESTE COMPARATIVO COLLECT CHAT ===\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n\n";

// Dados de teste para bpsegurosimediato.com.br (add_collect_chat.php)
$testData1 = [
    "NAME" => "TESTE BPSEGURO - " . date('Y-m-d H:i:s'),
    "NUMBER" => "11999887766",
    "CPF" => "12345678901",
    "PLACA" => "ABC1234",
    "CEP" => "01234567",
    "EMAIL" => "teste.bpseguro@exemplo.com",
    "gclid" => "test_bpseguro_" . time()
];

// Dados de teste para mdmidia.com.br (add_collect_chat.php)
$testData2 = [
    "NAME" => "TESTE MDMIDIA - " . date('Y-m-d H:i:s'),
    "NUMBER" => "11888776655",
    "CPF" => "98765432109",
    "PLACA" => "XYZ9876",
    "CEP" => "98765432",
    "EMAIL" => "teste.mdmidia@exemplo.com",
    "gclid" => "test_mdmidia_" . time()
];

// Função para fazer requisição
function testEndpoint($url, $data, $name) {
    echo "--- TESTANDO $name ---\n";
    echo "URL: $url\n";
    echo "Dados: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "Status HTTP: $httpCode\n";
    if ($error) {
        echo "Erro cURL: $error\n";
    }
    echo "Resposta: $response\n";
    echo "--- FIM TESTE $name ---\n\n";
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

// Teste 1: bpsegurosimediato.com.br (add_collect_chat.php)
echo "=== TESTE 1: BPSEGURO SIMEDIATO ===\n";
$result1 = testEndpoint(
    "https://bpsegurosimediato.com.br/add_collect_chat.php",
    $testData1,
    "BPSEGURO - add_collect_chat.php"
);

// Aguardar 2 segundos entre os testes
sleep(2);

// Teste 2: mdmidia.com.br (add_collect_chat.php)
echo "=== TESTE 2: MDMIDIA ===\n";
$result2 = testEndpoint(
    "https://mdmidia.com.br/add_collect_chat.php",
    $testData2,
    "MDMIDIA - add_collect_chat.php"
);

// Resumo dos resultados
echo "=== RESUMO DOS TESTES ===\n";
echo "BPSEGURO (add_collect_chat.php):\n";
echo "  Status: " . $result1['http_code'] . "\n";
echo "  Sucesso: " . ($result1['http_code'] == 200 ? 'SIM' : 'NÃO') . "\n";
echo "  Erro: " . ($result1['error'] ?: 'Nenhum') . "\n\n";

echo "MDMIDIA (add_collect_chat.php):\n";
echo "  Status: " . $result2['http_code'] . "\n";
echo "  Sucesso: " . ($result2['http_code'] == 200 ? 'SIM' : 'NÃO') . "\n";
echo "  Erro: " . ($result2['error'] ?: 'Nenhum') . "\n\n";

echo "=== PRÓXIMOS PASSOS ===\n";
echo "1. Verificar logs no bpsegurosimediato.com.br:\n";
echo "   - Arquivo: collect_chat_logs.txt\n";
echo "   - Buscar por: " . $testData1['NAME'] . "\n\n";

echo "2. Verificar logs no mdmidia.com.br:\n";
echo "   - Arquivo: logs_collect_chat.txt (ou similar)\n";
echo "   - Buscar por: " . $testData2['NAME'] . "\n\n";

echo "Teste concluído em: " . date('Y-m-d H:i:s') . "\n";
?>
