<?php

/**
 * TESTE WEBFLOW REAL - SIMULANDO PAYLOAD COMO ARRAY
 * 
 * Este arquivo simula exatamente o que o Webflow envia:
 * - Payload como ARRAY (não string)
 * - Dados aninhados corretamente
 * - Estrutura idêntica ao Webflow real
 */

echo "🚀 EXECUTANDO TESTE WEBFLOW REAL (PAYLOAD COMO ARRAY)\n";
echo "====================================================\n";

// Gerar dados únicos para o teste
$timestamp = date('Y-m-d-H-i-s');
$testName = "TESTE WEBFLOW REAL ARRAY $timestamp";
$testEmail = "teste.webflow.real.array.$timestamp@teste.com";

echo "📋 Dados do teste:\n";
echo "   Nome: $testName\n";
echo "   Email: $testEmail\n";
echo "   Timestamp: $timestamp\n";
echo "   URL: https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php\n\n";

// SIMULAR EXATAMENTE O QUE O WEBFLOW ENVIA (PAYLOAD COMO ARRAY)
$testPayload = [
    'triggerType' => 'form_submission',
    'payload' => [  // ← ARRAY, não string JSON!
        'name' => 'Home',
        'siteId' => '68f77ea29d6b098f6bcad795',
        'data' => json_encode([  // ← String JSON dentro do array
            'NOME' => $testName,
            'DDD-CELULAR' => '11',
            'CELULAR' => '999999999',
            'Email' => $testEmail,
            'CEP' => '01234567',
            'CPF' => '12345678901',
            'PLACA' => 'ABC1234',
            'ANO' => '2023',
            'MARCA' => 'Honda',
            'GCLID_FLD' => 'gclid_teste_webflow_real_' . $timestamp,
            'SEQUENCIA_FLD' => ''
        ]),
        'submittedAt' => date('c'),
        'id' => '68faa7b123456789',
        'formId' => '68f788bd5dc3f2ca4483eee0',
        'formElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f783',
        'pageId' => '68f77ea29d6b098f6bcad76f',
        'publishedPath' => '/',
        'pageUrl' => 'https://segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/',
        'schema' => json_encode([
            [
                'fieldName' => 'NOME',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f784'
            ],
            [
                'fieldName' => 'DDD-CELULAR',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f788'
            ],
            [
                'fieldName' => 'CELULAR',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f789'
            ],
            [
                'fieldName' => 'Email',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f78c'
            ],
            [
                'fieldName' => 'CEP',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f78d'
            ],
            [
                'fieldName' => 'CPF',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f78e'
            ],
            [
                'fieldName' => 'PLACA',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f78f'
            ],
            [
                'fieldName' => 'ANO',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f793'
            ],
            [
                'fieldName' => 'MARCA',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => 'ef8b88ef-5af9-ad83-b8f8-6d1162990897'
            ]
        ])
    ]
];

echo "📊 RESULTADO DA REQUISIÇÃO:\n";

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testPayload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Executar requisição
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Exibir resultado
echo "   HTTP Code: $httpCode\n";
if ($error) {
    echo "   Erro cURL: $error\n";
} else {
    echo "   Resposta: " . substr($response, 0, 200) . "...\n";
}

// Tentar extrair request_id da resposta
$responseData = json_decode($response, true);
$requestId = $responseData['data']['request_id'] ?? 'N/A';

if ($requestId !== 'N/A') {
    echo "\n✅ Request ID capturado: $requestId\n";

    echo "\n🔍 CONSULTANDO LOGS VIA SSH...\n";
    echo "================================\n";

    // Consultar logs via SSH
    $sshCommand = "ssh root@bpsegurosimediato.com.br \"grep -A 50 '$requestId' /var/www/html/dev/logs/travelangels_dev.txt\"";
    $logs = shell_exec($sshCommand);

    if ($logs) {
        echo "📋 LOGS ENCONTRADOS:\n";
        echo $logs . "\n";

        // Buscar logs específicos
        echo "🔍 BUSCANDO LOGS DE PROCESSAMENTO...\n";
        echo "=====================================\n";

        $processLogs = shell_exec("ssh root@bpsegurosimediato.com.br \"grep -A 20 '$requestId' /var/www/html/dev/logs/travelangels_dev.txt | grep -E '(api_v2_payload_decoded|data_processing_complete|field_mapping|lead_created|opportunity_created)'\"");

        if ($processLogs) {
            echo "📋 LOGS DE PROCESSAMENTO:\n";
            echo $processLogs . "\n";
        } else {
            echo "⚠️ Nenhum log de processamento encontrado\n";
        }

        // Buscar logs de erro
        echo "🔍 BUSCANDO LOGS DE ERRO...\n";
        echo "============================\n";

        $errorLogs = shell_exec("ssh root@bpsegurosimediato.com.br \"grep -A 10 '$requestId' /var/www/html/dev/logs/travelangels_dev.txt | grep -E '(error|failed|exception)'\"");

        if ($errorLogs) {
            echo "📋 LOGS DE ERRO:\n";
            echo $errorLogs . "\n";
        } else {
            echo "✅ Nenhum erro encontrado\n";
        }
    } else {
        echo "❌ Nenhum log encontrado para este request_id\n";
    }
} else {
    echo "⚠️ Request ID não encontrado na resposta\n";
}

echo "\n============================================================\n";
echo "✅ TESTE WEBFLOW REAL CONCLUÍDO\n";
echo "📊 Este teste simula exatamente o que o Webflow envia (payload como array)\n";
echo "🎯 Compare com os testes anteriores que usavam payload como string\n";
echo "============================================================\n";
