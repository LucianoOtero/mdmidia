<?php
/**
 * TESTE DO ENDPOINT add_collect_chat.php NO SERVIDOR MDMIDIA
 * 
 * Este programa testa o endpoint com dados fictícios para verificar:
 * - Conectividade com o servidor
 * - Processamento dos dados
 * - Criação do Lead no TravelAngels
 * - Criação da Oportunidade no FlyingDonkeys
 * - Logs gerados
 * 
 * Autor: Sistema de Testes Automatizados
 * Data: 2025-10-21
 * Versão: 1.0
 */

echo "=== TESTE DO ENDPOINT add_collect_chat.php ===\n";
echo "Servidor: mdmidia.com.br\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
echo "==============================================\n\n";

// Dados fictícios para teste
$testData = [
    'NAME' => 'TESTE AUTOMATIZADO - ' . date('Y-m-d H:i:s'),
    'NUMBER' => '11999887766',
    'CPF' => '12345678901',
    'CEP' => '01234567',
    'PLACA' => 'ABC1234',
    'EMAIL' => 'teste@exemplo.com',
    'gclid' => 'test_gclid_' . time()
];

echo "📋 DADOS DE TESTE:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

// Configuração do endpoint
$endpoint = 'https://mdmidia.com.br/add_collect_chat.php';

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
        'User-Agent: Teste-Automatizado-v1.0'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    
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
        'execution_time' => round(($endTime - $startTime) * 1000, 2) // em ms
    ];
}

// Executar o teste
$result = testEndpoint($endpoint, $testData);

echo "📊 RESULTADO DO TESTE:\n";
echo "======================\n";

// Status da conexão
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

// Tempo de execução
echo "⏱️  Tempo de Execução: " . $result['execution_time'] . "ms\n";

// Informações da conexão
echo "🔗 Informações da Conexão:\n";
echo "   - URL Final: " . $result['info']['url'] . "\n";
echo "   - Tempo Total: " . round($result['info']['total_time'], 3) . "s\n";
echo "   - Tempo DNS: " . round($result['info']['namelookup_time'], 3) . "s\n";
echo "   - Tempo Conexão: " . round($result['info']['connect_time'], 3) . "s\n";
echo "   - Tempo SSL: " . round($result['info']['appconnect_time'], 3) . "s\n";
echo "   - Tamanho Enviado: " . $result['info']['size_upload'] . " bytes\n";
echo "   - Tamanho Recebido: " . $result['info']['size_download'] . " bytes\n";

echo "\n📥 RESPOSTA DO SERVIDOR:\n";
echo "========================\n";

if ($result['response']) {
    // Tentar decodificar JSON
    $jsonResponse = json_decode($result['response'], true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✅ Resposta JSON válida:\n";
        echo json_encode($jsonResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        
        // Verificar campos específicos da resposta
        if (isset($jsonResponse['status'])) {
            echo "\n🎯 ANÁLISE DA RESPOSTA:\n";
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

echo "\n🔍 PRÓXIMOS PASSOS:\n";
echo "===================\n";
echo "1. Verificar logs no servidor mdmidia:\n";
echo "   - Arquivo: collect_chat_logs.txt\n";
echo "   - Buscar por: '" . $testData['NAME'] . "'\n\n";

echo "2. Verificar criação no TravelAngels:\n";
echo "   - URL: https://travelangels.com.br\n";
echo "   - Buscar por email: " . $testData['EMAIL'] . "\n\n";

echo "3. Verificar criação no FlyingDonkeys:\n";
echo "   - URL: https://flyingdonkeys.com.br\n";
echo "   - Buscar por email: " . $testData['EMAIL'] . "\n\n";

echo "4. Verificar oportunidade criada:\n";
echo "   - Verificar se Lead foi convertido em Oportunidade\n";
echo "   - Verificar campos de origem (source/leadSource)\n\n";

echo "=== FIM DO TESTE ===\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
?>
