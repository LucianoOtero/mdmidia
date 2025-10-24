<?php

/**
 * TESTE COMPLETO DO WEBHOOK SEM VALIDAÇÃO DE ASSINATURA
 * Testa o add_travelangels_dev.php sem validação de signature para focar nos logs de cURL
 */

// Gerar dados únicos para o teste
$timestamp = date('Y-m-d-H-i-s');
$testName = "TESTE JSON MALFORMADO $timestamp";
$testEmail = "teste.json.malformado.$timestamp@teste.com";

// Payload de teste MALFORMADO (exatamente como o Webflow envia)
$testPayload = [
    'triggerType' => 'form_submission',
    'payload' => '{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data":"{"NOME":"' . $testName . '"","DDD-CELULAR":"11","CELULAR":"999999999","Email":"' . $testEmail . '","CEP":"01234567","CPF":"12345678901","PLACA":"ABC1234","ANO":"2023","MARCA":"Honda","GCLID_FLD":"gclid_teste_logs_sem_sig_' . $timestamp . '","SEQUENCIA_FLD":""}","submittedAt":"' . date('c') . '","id":"68faa7b123456789","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"/","pageUrl":"https://segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/","schema":"[{\"fieldName\":\"NOME\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f784\"}]"}'
];

// Converter para JSON
$jsonPayload = json_encode($testPayload);

// Configurar cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $jsonPayload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
        // Sem headers de signature para bypassar validação
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false
]);

echo "🚀 EXECUTANDO TESTE DO WEBHOOK COM JSON MALFORMADO\n";
echo "==================================================\n";
echo "📋 Dados do teste:\n";
echo "   Nome: $testName\n";
echo "   Email: $testEmail\n";
echo "   Timestamp: $timestamp\n";
echo "   URL: https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php\n";
echo "\n";

// Executar requisição
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 RESULTADO DA REQUISIÇÃO:\n";
echo "   HTTP Code: $httpCode\n";
if ($error) {
    echo "   Erro cURL: $error\n";
}
echo "   Resposta: " . substr($response, 0, 200) . "...\n";
echo "\n";

// Tentar decodificar resposta
$responseData = json_decode($response, true);
if ($responseData && isset($responseData['data']['request_id'])) {
    $requestId = $responseData['data']['request_id'];
    echo "✅ Request ID capturado: $requestId\n";
    echo "\n";

    // Aguardar um momento para o log ser escrito
    sleep(3);

    // Consultar logs via SSH
    echo "🔍 CONSULTANDO LOGS VIA SSH...\n";
    echo "================================\n";

    $sshCommand = "grep -A 15 -B 5 '$requestId' /var/www/html/dev/logs/travelangels_dev.txt";
    $sshResult = shell_exec("ssh root@bpsegurosimediato.com.br \"$sshCommand\"");

    if ($sshResult) {
        echo "📋 LOGS ENCONTRADOS:\n";
        echo $sshResult;
        echo "\n";

        // Buscar especificamente pelos logs de cURL
        echo "🔍 BUSCANDO LOGS DE CURL...\n";
        echo "============================\n";

        $curlLogCommand = "grep -A 25 'curl_request_complete' /var/www/html/dev/logs/travelangels_dev.txt | tail -50";
        $curlLogs = shell_exec("ssh root@bpsegurosimediato.com.br \"$curlLogCommand\"");

        if ($curlLogs) {
            echo "📋 LOGS DE CURL ENCONTRADOS:\n";
            echo $curlLogs;
        } else {
            echo "❌ Nenhum log de cURL encontrado\n";
        }

        // Buscar logs de erro específicos
        echo "\n🔍 BUSCANDO LOGS DE ERRO...\n";
        echo "============================\n";

        $errorLogCommand = "grep -A 10 -B 5 'exception\\|error\\|failed' /var/www/html/dev/logs/travelangels_dev.txt | tail -30";
        $errorLogs = shell_exec("ssh root@bpsegurosimediato.com.br \"$errorLogCommand\"");

        if ($errorLogs) {
            echo "📋 LOGS DE ERRO ENCONTRADOS:\n";
            echo $errorLogs;
        } else {
            echo "✅ Nenhum erro encontrado nos logs\n";
        }
    } else {
        echo "❌ Nenhum log encontrado para o request_id: $requestId\n";
    }
} else {
    echo "❌ Não foi possível capturar o request_id da resposta\n";
    echo "   Resposta completa: $response\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ TESTE CONCLUÍDO\n";
echo "📊 Verifique os logs acima para identificar o problema\n";
echo "🎯 Compare com o cURL manual que funcionou\n";
echo str_repeat("=", 60) . "\n";
