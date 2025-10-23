<?php
// ============================================================================
// TESTE DO REGISTRO JOSÉ - ESTRUTURA WEBFLOW
// ============================================================================

echo "=== TESTE DO REGISTRO JOSÉ ===\n";
echo "Testando com a estrutura Webflow...\n\n";

// URL do endpoint
$url = 'https://mdmidia.com.br/add_travelangels.php';

// Array com o registro do José
$testData = [
    [
        'name' => 'jOSÉ',
        'email' => 'joseantonio.rodrigues@portaaberta.org.br',
        'ddd' => '11',
        'celular' => '94185-6341',
        'cep' => '',
        'cpf' => '',
        'placa' => 'FPA-0G13',
        'ano' => '2023',
        'marca' => 'RENAULT / MASTER MINIBUSL3',
        'gclid' => 'CjwKCAjwr8LHBhBKEiwAy47uUke-I3pVr0kUTxGHG6WupURKoZeGjdVLw4Vfjhz3kxbr1aK_zpaQehoCO0sQAvD_BwE'
    ]
];

$totalTests = count($testData);
$successCount = 0;
$errorCount = 0;

foreach ($testData as $index => $person) {
    echo "--- TESTE " . ($index + 1) . "/$totalTests ---\n";
    echo "📋 Nome: {$person['name']}\n";
    echo "📧 Email: {$person['email']}\n";
    echo "📱 Telefone: {$person['ddd']}{$person['celular']}\n";
    echo "🚗 Veículo: {$person['marca']} ({$person['ano']})\n";
    echo "🔢 Placa: {$person['placa']}\n";

    // Estrutura JSON simulando Webflow
    $data = [
        'name' => 'Home Seguro Auto',
        'site' => '59eb807f9d16950001e202af',
        'data' => [
            'NOME' => $person['name'],
            'DDD-CELULAR' => $person['ddd'],
            'CELULAR' => $person['celular'],
            'Email' => $person['email'],
            'CEP' => $person['cep'],
            'CPF' => $person['cpf'],
            'PLACA' => $person['placa'],
            'ANO' => $person['ano'],
            'MARCA' => $person['marca'],
            'GCLID_FLD' => $person['gclid'],
            'SEQUENCIA_FLD' => ''
        ],
        'd' => date('c'),
        '_id' => '68effc454b7075cee10ac' . str_pad($index, 3, '0', STR_PAD_LEFT),
        'formId' => '687d71cae526515402fc9566',
        'formElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f783',
        'pageId' => '59eb807f9d16950001e202b2',
        'publishedPath' => '/',
        'pageUrl' => 'https://www.segurosimediato.com.br/?gad_source=1&gad_campaignid=21287198336&gclid=' . $person['gclid']
    ];

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
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    echo "🔗 Enviando requisição...\n";

    // Executar requisição
    $startTime = microtime(true);
    $response = curl_exec($ch);
    $endTime = microtime(true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    $executionTime = round(($endTime - $startTime) * 1000, 2);

    echo "⏱️ Tempo: {$executionTime}ms | HTTP: $httpCode\n";

    if ($curlError) {
        echo "❌ cURL Error: $curlError\n";
        $errorCount++;
    } else {
        echo "✅ cURL executado sem erros\n";
    }

    echo "📄 Resposta: $response\n";

    // Verificar se a resposta é JSON válido
    $responseData = json_decode($response, true);
    if ($responseData) {
        echo "✅ Resposta JSON válida:\n";
        foreach ($responseData as $key => $value) {
            echo "   $key: $value\n";
        }

        // Verificar sucesso
        if ($httpCode == 200 && isset($responseData['status']) && $responseData['status'] === 'success') {
            echo "🎉 SUCESSO!\n";
            $successCount++;
        } else {
            echo "❌ FALHA!\n";
            $errorCount++;
        }
    } else {
        echo "⚠️ Resposta não é JSON válido\n";
        $errorCount++;
    }

    echo "\n" . str_repeat("-", 60) . "\n\n";

    // Pausa entre testes para não sobrecarregar o servidor
    sleep(3);
}

// Resumo final
echo "=== RESUMO FINAL ===\n";
echo "📊 Total de testes: $totalTests\n";
echo "✅ Sucessos: $successCount\n";
echo "❌ Erros: $errorCount\n";
echo "📈 Taxa de sucesso: " . round(($successCount / $totalTests) * 100, 1) . "%\n\n";

if ($successCount == $totalTests) {
    echo "🎉 TESTE PASSOU!\n";
    echo "✅ O registro do José foi processado com sucesso.\n";
    echo "✅ A correção das regras de duplicação está funcionando.\n";
} else {
    echo "❌ TESTE FALHOU!\n";
    echo "⚠️ Verificar logs para identificar o problema.\n";
}

echo "\n✅ TESTE CONCLUÍDO!\n";
