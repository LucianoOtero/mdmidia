<?php
// Teste para add_travelangels_v4.php e add_collect_chat_v4.php
// Verifica se ambos os endpoints enviam dados para TravelAngels e FlyingDonkeys

echo "=== TESTE FLYINGDONKEYS V4 ===\n";
echo "Testando duplicação de dados entre TravelAngels e FlyingDonkeys\n\n";

// Dados de teste para TravelAngels (formato Webflow)
$testDataTravelAngels = [
    "data" => [
        "NOME" => "JOÃO SILVA SANTOS",
        "DDD-CELULAR" => "11",
        "CELULAR" => "987654321",
        "Email" => "joao.travelangels@email.com",
        "CEP" => "01234-567",
        "CPF" => "123.456.789-00",
        "MARCA" => "Toyota",
        "PLACA" => "ABC1234",
        "ANO" => "2020",
        "GCLID_FLD" => "test_gclid_travelangels"
    ],
    "d" => "2025-10-13 14:30:00",
    "name" => "travelangels.webflow"
];

// Dados de teste para Collect.chat (formato Collect.chat)
$testDataCollectChat = [
    "NAME" => "MARIA SANTOS OLIVEIRA",
    "NUMBER" => "11987654321",
    "CPF" => "98765432100",
    "CEP" => "56789-012",
    "PLACA" => "XYZ9876",
    "EMAIL" => "maria.collectchat@email.com",
    "gclid" => "test_gclid_collectchat"
];

// URLs dos endpoints
$urlTravelAngels = 'https://mdmidia.com.br/add_travelangels_v4.php';
$urlCollectChat = 'https://mdmidia.com.br/add_collect_chat_v4.php';

// Função para executar teste
function executarTeste($url, $data, $nomeTeste) {
    echo "=== TESTE: $nomeTeste ===\n";
    echo "URL: $url\n";
    echo "Dados enviados:\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    
    // Configurar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    // Executar requisição
    echo "Enviando requisição...\n";
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    // Exibir resultados
    echo "=== RESULTADO ===\n";
    echo "HTTP Code: $httpCode\n";
    
    if ($error) {
        echo "Erro cURL: $error\n";
        return false;
    } else {
        echo "Resposta:\n";
        echo $response . "\n";
    }
    
    // Verificar se foi sucesso
    if ($httpCode == 200) {
        echo "\n✅ TESTE PASSOU - HTTP 200 OK\n";
        return true;
    } else {
        echo "\n❌ TESTE FALHOU - HTTP $httpCode\n";
        return false;
    }
}

// Executar testes
$resultado1 = executarTeste($urlTravelAngels, $testDataTravelAngels, "TRAVELANGELS V4");
echo "\n" . str_repeat("=", 80) . "\n\n";

$resultado2 = executarTeste($urlCollectChat, $testDataCollectChat, "COLLECT.CHAT V4");
echo "\n" . str_repeat("=", 80) . "\n\n";

// Resumo dos resultados
echo "=== RESUMO DOS TESTES ===\n";
echo "TravelAngels V4: " . ($resultado1 ? "✅ SUCESSO" : "❌ FALHOU") . "\n";
echo "Collect.chat V4: " . ($resultado2 ? "✅ SUCESSO" : "❌ FALHOU") . "\n\n";

if ($resultado1 && $resultado2) {
    echo "🎉 TODOS OS TESTES PASSARAM!\n";
    echo "Ambos os endpoints estão funcionando e enviando dados para:\n";
    echo "• TravelAngels (https://travelangels.com.br)\n";
    echo "• FlyingDonkeys (https://flyingdonkeys.com.br)\n\n";
} else {
    echo "⚠️  ALGUNS TESTES FALHARAM!\n";
    echo "Verifique os logs e as respostas acima.\n\n";
}

echo "=== PRÓXIMOS PASSOS ===\n";
echo "1. Verificar logs do TravelAngels: https://mdmidia.com.br/logs_travelangels.txt\n";
echo "2. Verificar logs do Collect.chat: https://mdmidia.com.br/collect_chat_logs.txt\n";
echo "3. Verificar se os leads foram criados em ambos os EspoCRMs:\n";
echo "   • TravelAngels: https://travelangels.com.br\n";
echo "   • FlyingDonkeys: https://flyingdonkeys.com.br\n";
echo "4. Validar se os dados estão corretos em ambos os sistemas\n";
echo "5. Verificar se as API keys estão funcionando em ambos os endpoints\n\n";

echo "=== DADOS DE TESTE ENVIADOS ===\n";
echo "TravelAngels:\n";
echo "• Nome: JOÃO SILVA SANTOS\n";
echo "• Email: joao.travelangels@email.com\n";
echo "• Telefone: 11987654321\n";
echo "• Placa: ABC1234\n";
echo "• GCLID: test_gclid_travelangels\n\n";

echo "Collect.chat:\n";
echo "• Nome: MARIA SANTOS OLIVEIRA\n";
echo "• Email: maria.collectchat@email.com\n";
echo "• Telefone: 11987654321\n";
echo "• Placa: XYZ9876\n";
echo "• GCLID: test_gclid_collectchat\n\n";

echo "=== VALIDAÇÃO ESPERADA ===\n";
echo "Nos logs, você deve ver:\n";
echo "• 'TravelAngels - Resposta: [dados do lead]'\n";
echo "• 'FlyingDonkeys - Resposta: [dados do lead]'\n";
echo "• Ou mensagens de erro específicas para cada sistema\n";
echo "• Ambos os sistemas devem receber os mesmos dados\n";
echo "• As API keys devem funcionar em ambos os endpoints\n";
?>
