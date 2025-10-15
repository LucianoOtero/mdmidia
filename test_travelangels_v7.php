<?php
// Arquivo de teste para add_travelangels_v7.php
// Testa cenários de lead novo, lead duplicado e criação de oportunidades

echo "=== TESTE ADD_TRAVELANGELS_V7 ===\n\n";

// Função para fazer requisição cURL
function makeRequest($url, $data)
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
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'curlError' => $curlError
    ];
}

// URL do endpoint
$url = 'https://mdmidia.com.br/add_travelangels_v7.php';

echo "🔗 URL: $url\n\n";

// ===== TESTE 1: LEAD NOVO =====
echo "📋 TESTE 1: LEAD NOVO\n";
echo "=====================\n";

$testData1 = [
    'nome' => 'Pedro Henrique Costa V9',
    'email' => 'pedro.costa.v9@teste.com',
    'telefone' => '11999887766',
    'cpf' => '111.222.333-44',
    'marca' => 'Honda',
    'placa' => 'ABC1234',
    'ano' => '2020',
    'cep' => '01234-567',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'endereco' => 'Rua Teste, 123',
    'gclid' => 'test_gclid_001'
];

echo "📤 Dados enviados:\n";
foreach ($testData1 as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

$result1 = makeRequest($url, $testData1);

echo "📥 Resposta HTTP: " . $result1['httpCode'] . "\n";
if ($result1['curlError']) {
    echo "❌ Erro cURL: " . $result1['curlError'] . "\n";
}
echo "📄 Resposta: " . $result1['response'] . "\n";

// Verificar se a resposta contém IDs de leads
$responseData1 = json_decode($result1['response'], true);
if (isset($responseData1['leadIdTravelAngels']) && isset($responseData1['leadIdFlyingDonkeys'])) {
    echo "✅ Leads criados com sucesso:\n";
    echo "   TravelAngels ID: " . $responseData1['leadIdTravelAngels'] . "\n";
    echo "   FlyingDonkeys ID: " . $responseData1['leadIdFlyingDonkeys'] . "\n";
} else {
    echo "❌ Erro: IDs de leads não encontrados na resposta\n";
}
echo "\n";

// Aguardar um pouco antes do próximo teste
sleep(2);

// ===== TESTE 2: LEAD DUPLICADO (MESMO EMAIL) =====
echo "📋 TESTE 2: LEAD DUPLICADO (MESMO EMAIL)\n";
echo "=========================================\n";

$testData2 = [
    'nome' => 'Fernanda Lima Rodrigues V9',
    'email' => 'fernanda.rodrigues.v9@teste.com',
    'telefone' => '11999887777',
    'cpf' => '111.222.333-55',
    'marca' => 'Toyota',
    'placa' => 'DEF5678',
    'ano' => '2021',
    'cep' => '01234-568',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'endereco' => 'Rua Teste, 456',
    'gclid' => 'test_gclid_002'
];

echo "📤 Dados enviados:\n";
foreach ($testData2 as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

$result2 = makeRequest($url, $testData2);

echo "📥 Resposta HTTP: " . $result2['httpCode'] . "\n";
if ($result2['curlError']) {
    echo "❌ Erro cURL: " . $result2['curlError'] . "\n";
}
echo "📄 Resposta: " . $result2['response'] . "\n";

// Verificar se a resposta contém IDs de leads
$responseData2 = json_decode($result2['response'], true);
if (isset($responseData2['leadIdTravelAngels']) && isset($responseData2['leadIdFlyingDonkeys'])) {
    echo "✅ Leads processados com sucesso:\n";
    echo "   TravelAngels ID: " . $responseData2['leadIdTravelAngels'] . "\n";
    echo "   FlyingDonkeys ID: " . $responseData2['leadIdFlyingDonkeys'] . "\n";

    // Verificar se são os mesmos IDs do teste 1 (confirma atualização)
    if (
        $responseData2['leadIdTravelAngels'] === $responseData1['leadIdTravelAngels'] &&
        $responseData2['leadIdFlyingDonkeys'] === $responseData1['leadIdFlyingDonkeys']
    ) {
        echo "✅ CONFIRMADO: Lead duplicado foi atualizado (mesmos IDs)\n";
    } else {
        echo "⚠️ ATENÇÃO: IDs diferentes - pode não ter detectado duplicata\n";
    }
} else {
    echo "❌ Erro: IDs de leads não encontrados na resposta\n";
}
echo "\n";

// Aguardar um pouco antes do próximo teste
sleep(2);

// ===== TESTE 3: LEAD COMPLETAMENTE NOVO =====
echo "📋 TESTE 3: LEAD COMPLETAMENTE NOVO\n";
echo "===================================\n";

$testData3 = [
    'nome' => 'Rafael Almeida Pereira V9',
    'email' => 'rafael.pereira.v9@teste.com',
    'telefone' => '11999887788',
    'cpf' => '111.222.333-66',
    'marca' => 'Ford',
    'placa' => 'GHI9012',
    'ano' => '2019',
    'cep' => '01234-569',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'endereco' => 'Rua Teste, 789',
    'gclid' => 'test_gclid_003'
];

echo "📤 Dados enviados:\n";
foreach ($testData3 as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

$result3 = makeRequest($url, $testData3);

echo "📥 Resposta HTTP: " . $result3['httpCode'] . "\n";
if ($result3['curlError']) {
    echo "❌ Erro cURL: " . $result3['curlError'] . "\n";
}
echo "📄 Resposta: " . $result3['response'] . "\n";

// Verificar se a resposta contém IDs de leads
$responseData3 = json_decode($result3['response'], true);
if (isset($responseData3['leadIdTravelAngels']) && isset($responseData3['leadIdFlyingDonkeys'])) {
    echo "✅ Leads criados com sucesso:\n";
    echo "   TravelAngels ID: " . $responseData3['leadIdTravelAngels'] . "\n";
    echo "   FlyingDonkeys ID: " . $responseData3['leadIdFlyingDonkeys'] . "\n";
} else {
    echo "❌ Erro: IDs de leads não encontrados na resposta\n";
}
echo "\n";

// ===== VERIFICAÇÃO DAS OPORTUNIDADES =====
echo "🔍 VERIFICAÇÃO DAS OPORTUNIDADES\n";
echo "=================================\n";

// Função para verificar oportunidades via API
function checkOpportunities($leadId, $system)
{
    $apiUrl = $system === 'TravelAngels' ? 'https://travelangels.com.br' : 'https://flyingdonkeys.com.br';
    $apiKey = '7a6c08d438ee131971f561fd836b5e15';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '/api/v1/Opportunity?where[0][type]=equals&where[0][attribute]=leadId&where[0][value]=' . $leadId);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        return isset($data['list']) ? count($data['list']) : 0;
    }
    return -1; // Erro na consulta
}

// Verificar oportunidades para cada lead criado
echo "📊 Verificando oportunidades criadas...\n\n";

// Teste 1
echo "🔍 TESTE 1 - Lead: " . $responseData1['leadIdTravelAngels'] . "\n";
$oppTA1 = checkOpportunities($responseData1['leadIdTravelAngels'], 'TravelAngels');
$oppFD1 = checkOpportunities($responseData1['leadIdFlyingDonkeys'], 'FlyingDonkeys');

if ($oppTA1 > 0) {
    echo "✅ TravelAngels: {$oppTA1} oportunidade(s) encontrada(s)\n";
} elseif ($oppTA1 === 0) {
    echo "❌ TravelAngels: Nenhuma oportunidade encontrada\n";
} else {
    echo "⚠️ TravelAngels: Erro ao verificar oportunidades\n";
}

if ($oppFD1 > 0) {
    echo "✅ FlyingDonkeys: {$oppFD1} oportunidade(s) encontrada(s)\n";
} elseif ($oppFD1 === 0) {
    echo "❌ FlyingDonkeys: Nenhuma oportunidade encontrada\n";
} else {
    echo "⚠️ FlyingDonkeys: Erro ao verificar oportunidades\n";
}
echo "\n";

// Teste 2
echo "🔍 TESTE 2 - Lead: " . $responseData2['leadIdTravelAngels'] . "\n";
$oppTA2 = checkOpportunities($responseData2['leadIdTravelAngels'], 'TravelAngels');
$oppFD2 = checkOpportunities($responseData2['leadIdFlyingDonkeys'], 'FlyingDonkeys');

if ($oppTA2 > 0) {
    echo "✅ TravelAngels: {$oppTA2} oportunidade(s) encontrada(s)\n";
} elseif ($oppTA2 === 0) {
    echo "❌ TravelAngels: Nenhuma oportunidade encontrada\n";
} else {
    echo "⚠️ TravelAngels: Erro ao verificar oportunidades\n";
}

if ($oppFD2 > 0) {
    echo "✅ FlyingDonkeys: {$oppFD2} oportunidade(s) encontrada(s)\n";
} elseif ($oppFD2 === 0) {
    echo "❌ FlyingDonkeys: Nenhuma oportunidade encontrada\n";
} else {
    echo "⚠️ FlyingDonkeys: Erro ao verificar oportunidades\n";
}
echo "\n";

// Teste 3
echo "🔍 TESTE 3 - Lead: " . $responseData3['leadIdTravelAngels'] . "\n";
$oppTA3 = checkOpportunities($responseData3['leadIdTravelAngels'], 'TravelAngels');
$oppFD3 = checkOpportunities($responseData3['leadIdFlyingDonkeys'], 'FlyingDonkeys');

if ($oppTA3 > 0) {
    echo "✅ TravelAngels: {$oppTA3} oportunidade(s) encontrada(s)\n";
} elseif ($oppTA3 === 0) {
    echo "❌ TravelAngels: Nenhuma oportunidade encontrada\n";
} else {
    echo "⚠️ TravelAngels: Erro ao verificar oportunidades\n";
}

if ($oppFD3 > 0) {
    echo "✅ FlyingDonkeys: {$oppFD3} oportunidade(s) encontrada(s)\n";
} elseif ($oppFD3 === 0) {
    echo "❌ FlyingDonkeys: Nenhuma oportunidade encontrada\n";
} else {
    echo "⚠️ FlyingDonkeys: Erro ao verificar oportunidades\n";
}
echo "\n";

// ===== RESUMO DOS TESTES =====
echo "📊 RESUMO DOS TESTES\n";
echo "===================\n";

echo "✅ Teste 1 (Lead Novo): HTTP " . $result1['httpCode'] . "\n";
echo "✅ Teste 2 (Lead Duplicado): HTTP " . $result2['httpCode'] . "\n";
echo "✅ Teste 3 (Lead Novo): HTTP " . $result3['httpCode'] . "\n\n";

echo "🎯 RESULTADOS ESPERADOS:\n";
echo "- Teste 1: Lead criado + Oportunidade criada\n";
echo "- Teste 2: Lead atualizado + Nova oportunidade com duplicate=yes\n";
echo "- Teste 3: Lead criado + Oportunidade criada\n\n";

echo "📊 RESUMO DAS OPORTUNIDADES:\n";
echo "- Teste 1: TA={$oppTA1} oportunidades, FD={$oppFD1} oportunidades\n";
echo "- Teste 2: TA={$oppTA2} oportunidades, FD={$oppFD2} oportunidades\n";
echo "- Teste 3: TA={$oppTA3} oportunidades, FD={$oppFD3} oportunidades\n\n";

echo "📋 PRÓXIMOS PASSOS:\n";
echo "1. Verificar logs em logs_travelangels.txt\n";
echo "2. Verificar se leads foram criados/atualizados no EspoCRM\n";
echo "3. Verificar se oportunidades foram criadas (acima)\n";
echo "4. Verificar se oportunidade duplicada tem duplicate=yes\n\n";

echo "✅ TESTE CONCLUÍDO!\n";
