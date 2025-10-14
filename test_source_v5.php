<?php
// Teste para validar os endpoints _v5 com campo source
// Arquivo: test_source_v5.php

echo "🧪 TESTE DOS ENDPOINTS _V5 COM CAMPO SOURCE\n";
echo "==========================================\n\n";

// Configurações
$baseUrl = 'https://mdmidia.com.br';
$collectChatEndpoint = $baseUrl . '/add_collect_chat_v5.php';
$travelAngelsEndpoint = $baseUrl . '/add_travelangels_v5.php';

// Dados de teste para Collect Chat
$collectChatData = [
    'NAME' => 'Maria Silva Santos',
    'NUMBER' => '11999887766',
    'CPF' => '12345678901',
    'CEP' => '01234-567',
    'EMAIL' => 'maria.silva@email.com',
    'PLACA' => 'ABC1234',
    'gclid' => 'test_gclid_collect_chat'
];

// Dados de teste para TravelAngels (Webflow)
$travelAngelsData = [
    'data' => [
        'NOME' => 'João Carlos Oliveira',
        'DDD-CELULAR' => '011',
        'CELULAR' => '987654321',
        'Email' => 'joao.oliveira@email.com',
        'CEP' => '04567-890',
        'CPF' => '98765432100',
        'MARCA' => 'Honda',
        'PLACA' => 'XYZ9876',
        'ANO' => '2021',
        'GCLID_FLD' => 'test_gclid_travelangels'
    ],
    'd' => date('Y-m-d H:i:s'),
    'name' => 'segurosimediato.com.br'
];

// Função para fazer requisição POST
function makePostRequest($url, $data)
{
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    return [
        'response' => $response,
        'http_code' => $httpCode,
        'curl_error' => $curlError
    ];
}

echo "📋 TESTE 1: Collect Chat v5\n";
echo "----------------------------\n";
echo "Endpoint: $collectChatEndpoint\n";
echo "Dados enviados:\n";
echo "- Nome: " . $collectChatData['NAME'] . "\n";
echo "- Telefone: " . $collectChatData['NUMBER'] . "\n";
echo "- Email: " . $collectChatData['EMAIL'] . "\n";
echo "- Placa: " . $collectChatData['PLACA'] . "\n";
echo "- Source esperado: 'Collect Chat'\n\n";

$result1 = makePostRequest($collectChatEndpoint, $collectChatData);

echo "Resposta HTTP: " . $result1['http_code'] . "\n";
if ($result1['curl_error']) {
    echo "Erro cURL: " . $result1['curl_error'] . "\n";
} else {
    echo "Resposta: " . $result1['response'] . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n\n";

echo "📋 TESTE 2: TravelAngels v5\n";
echo "----------------------------\n";
echo "Endpoint: $travelAngelsEndpoint\n";
echo "Dados enviados:\n";
echo "- Nome: " . $travelAngelsData['data']['NOME'] . "\n";
echo "- Telefone: " . $travelAngelsData['data']['DDD-CELULAR'] . $travelAngelsData['data']['CELULAR'] . "\n";
echo "- Email: " . $travelAngelsData['data']['Email'] . "\n";
echo "- Placa: " . $travelAngelsData['data']['PLACA'] . "\n";
echo "- Source esperado: 'Site'\n\n";

$result2 = makePostRequest($travelAngelsEndpoint, $travelAngelsData);

echo "Resposta HTTP: " . $result2['http_code'] . "\n";
if ($result2['curl_error']) {
    echo "Erro cURL: " . $result2['curl_error'] . "\n";
} else {
    echo "Resposta: " . $result2['response'] . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n\n";

// Verificação dos resultados
echo "📊 RESUMO DOS TESTES\n";
echo "====================\n";

$collectChatSuccess = ($result1['http_code'] == 200 && !$result1['curl_error']);
$travelAngelsSuccess = ($result2['http_code'] == 200 && !$result2['curl_error']);

echo "Collect Chat v5: " . ($collectChatSuccess ? "✅ SUCESSO" : "❌ FALHA") . "\n";
echo "TravelAngels v5: " . ($travelAngelsSuccess ? "✅ SUCESSO" : "❌ FALHA") . "\n\n";

if ($collectChatSuccess && $travelAngelsSuccess) {
    echo "🎉 TODOS OS TESTES PASSARAM!\n";
    echo "Os endpoints _v5 estão funcionando corretamente.\n";
    echo "Verifique os logs para confirmar se o campo 'source' foi incluído:\n";
    echo "- collect_chat_logs.txt\n";
    echo "- logs_travelangels.txt\n\n";
    echo "🔍 PRÓXIMO PASSO: Verificar no EspoCRM se os leads foram criados com o campo 'source' correto.\n";
} else {
    echo "⚠️  ALGUNS TESTES FALHARAM!\n";
    echo "Verifique os logs e corrija os problemas antes de prosseguir.\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Teste concluído em: " . date('Y-m-d H:i:s') . "\n";
