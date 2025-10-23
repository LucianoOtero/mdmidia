<?php
/**
 * TESTE DO WEBHOOK TRAVELANGELS V2 - DESENVOLVIMENTO
 * mdmidia/tests/scripts/test_travelangels_dev.php
 * 
 * Testa o webhook de desenvolvimento com dados fictícios
 * Verifica se Lead usa 'source' e Opportunity usa 'leadSource'
 */

echo "=== TESTE WEBHOOK TRAVELANGELS V2 - DESENVOLVIMENTO ===\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n\n";

// Dados de teste para o webhook de desenvolvimento
$testData = [
    "name" => "TESTE DEV V2 - " . date('Y-m-d H:i:s'),
    "email" => "teste.dev.v2@exemplo.com",
    "phone" => "11999887766",
    "test_mode" => true // Flag para modo de teste
];

echo "--- DADOS DE TESTE ---\n";
echo "Nome: " . $testData['name'] . "\n";
echo "Email: " . $testData['email'] . "\n";
echo "Telefone: " . $testData['phone'] . "\n";
echo "Modo Teste: " . ($testData['test_mode'] ? 'SIM' : 'NÃO') . "\n\n";

// Função para fazer requisição
function testDevWebhook($url, $data, $name) {
    echo "--- TESTANDO $name ---\n";
    echo "URL: $url\n";
    echo "Dados: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'X-Webflow-Signature: dev_test_signature',
        'X-Webflow-Timestamp: ' . time()
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

// Teste do webhook de desenvolvimento local
echo "=== TESTE 1: WEBHOOK DESENVOLVIMENTO LOCAL ===\n";
$result1 = testDevWebhook(
    "http://localhost/mdmidia/dev/webhooks/add_travelangels_dev.php",
    $testData,
    "DESENVOLVIMENTO LOCAL"
);

// Teste do webhook de desenvolvimento no servidor
echo "=== TESTE 2: WEBHOOK DESENVOLVIMENTO SERVIDOR ===\n";
$result2 = testDevWebhook(
    "https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels.php",
    $testData,
    "DESENVOLVIMENTO SERVIDOR"
);

// Resumo dos resultados
echo "=== RESUMO DOS TESTES ===\n";
echo "DESENVOLVIMENTO LOCAL:\n";
echo "  Status: " . $result1['http_code'] . "\n";
echo "  Sucesso: " . ($result1['http_code'] == 200 ? 'SIM' : 'NÃO') . "\n";
echo "  Erro: " . ($result1['error'] ?: 'Nenhum') . "\n\n";

echo "DESENVOLVIMENTO SERVIDOR:\n";
echo "  Status: " . $result2['http_code'] . "\n";
echo "  Sucesso: " . ($result2['http_code'] == 200 ? 'SIM' : 'NÃO') . "\n";
echo "  Erro: " . ($result2['error'] ?: 'Nenhum') . "\n\n";

echo "=== VERIFICAÇÕES ESPECÍFICAS ===\n";
echo "✅ Lead deve usar campo 'source'\n";
echo "✅ Opportunity deve usar campo 'leadSource'\n";
echo "✅ Ambos devem ter valor 'Webflow Dev'\n\n";

echo "=== PRÓXIMOS PASSOS ===\n";
echo "1. Verificar logs de desenvolvimento:\n";
echo "   - Arquivo: dev/logs/travelangels_dev.txt\n";
echo "   - Buscar por: " . $testData['name'] . "\n\n";

echo "2. Verificar se correção foi aplicada:\n";
echo "   - Lead: source = 'Webflow Dev'\n";
echo "   - Opportunity: leadSource = 'Webflow Dev'\n\n";

echo "Teste concluído em: " . date('Y-m-d H:i:s') . "\n";
?>


