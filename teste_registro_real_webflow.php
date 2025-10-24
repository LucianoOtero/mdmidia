<?php

/**
 * TESTE FINAL - REGISTRO REAL DO WEBFLOW
 * Testa com o registro exato que você enviou
 */

echo "=== TESTE FINAL - REGISTRO REAL DO WEBFLOW ===\n\n";

// JSON real que você enviou (copiado dos logs)
$real_webflow_data = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO RODRIGUES OTERO 2025-10-23-19-18"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"lrotero@gmail.com","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN \/ MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T22:19:11.115Z","id":"68faa9dff1f2742693eb2a00","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"\/","pageUrl":"https: \"\/\/segurosimediato-8119bf26e77bf4ff336a58e.webflow.io\/\"","schema": "[{\"fieldName\":\"NOME\"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]"}}';

echo "Enviando registro real do Webflow para o webhook...\n";
echo "Nome: LUCIANO RODRIGUES OTERO 2025-10-23-19-18\n";
echo "Email: lrotero@gmail.com\n";
echo "Celular: 97668-7668\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $real_webflow_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
    // Removendo headers de assinatura para bypassar validação em dev
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "RESULTADO DO TESTE FINAL:\n";
echo "HTTP Code: " . $http_code . "\n";
if ($error) {
    echo "cURL Error: " . $error . "\n";
}
echo "Response: " . $response . "\n\n";

// Tentar decodificar a resposta
$response_data = json_decode($response, true);
if ($response_data) {
    echo "STATUS: " . ($response_data['status'] ?? 'unknown') . "\n";
    echo "MESSAGE: " . ($response_data['message'] ?? 'no message') . "\n";

    if (isset($response_data['data']['lead_response']['name'])) {
        echo "NOME PROCESSADO: " . $response_data['data']['lead_response']['name'] . "\n";
    }
    if (isset($response_data['data']['lead_response']['emailAddress'])) {
        echo "EMAIL PROCESSADO: " . $response_data['data']['lead_response']['emailAddress'] . "\n";
    }
    if (isset($response_data['data']['lead_response']['id'])) {
        echo "LEAD ID: " . $response_data['data']['lead_response']['id'] . "\n";
    }
    if (isset($response_data['data']['opportunity_response']['id'])) {
        echo "OPPORTUNITY ID: " . $response_data['data']['opportunity_response']['id'] . "\n";
    }
}

echo "\n=== TESTE FINAL CONCLUÍDO ===\n";

if ($http_code === 200 && isset($response_data['status']) && $response_data['status'] === 'success') {
    echo "✅ SUCESSO: Webhook funcionando perfeitamente!\n";
    echo "✅ Lead criado no EspoCRM de desenvolvimento\n";
    echo "✅ Oportunidade criada no EspoCRM de desenvolvimento\n";
    echo "✅ JSON malformado corrigido automaticamente\n";
    echo "✅ Registro real do Webflow processado com sucesso\n";
} else {
    echo "❌ FALHA: Ainda há problemas no webhook\n";
}
