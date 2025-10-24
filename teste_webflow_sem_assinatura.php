<?php

/**
 * TESTE LOCAL - SIMULAÇÃO EXATA DO WEBFLOW API V2 (SEM VALIDAÇÃO DE ASSINATURA)
 * Este script simula exatamente o que o Webflow envia para o webhook
 * Bypassa a validação de assinatura para testar o processamento dos dados
 */

echo "=== TESTE LOCAL - SIMULAÇÃO WEBFLOW API V2 (SEM ASSINATURA) ===\n\n";

// Simular exatamente o payload que o Webflow envia (baseado nos logs reais)
$webflow_payload = [
    'triggerType' => 'form_submission',
    'payload' => json_encode([
        'name' => 'Home',
        'siteId' => '68f77ea29d6b098f6bcad795',
        'data' => [
            'NOME' => 'LUCIANO TESTE LOCAL 2025-10-23-18-35',
            'DDD-CELULAR' => '11',
            'CELULAR' => '97668-7668',
            'Email' => 'luciano.teste@local.com',
            'CEP' => '03317-000',
            'CPF' => '085.546.078-48',
            'PLACA' => 'FPG-8D63',
            'ANO' => '2016',
            'MARCA' => 'NISSAN / MARCH 16SV',
            'GCLID_FLD' => '',
            'SEQUENCIA_FLD' => ''
        ],
        'submittedAt' => '2025-10-23T21:35:00.000Z',
        'id' => '68fa9b5c84d02',
        'formId' => '68f788bd5dc3f2ca4483eee0',
        'formElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f783',
        'pageId' => '68f77ea29d6b098f6bcad76f',
        'publishedPath' => '/',
        'pageUrl' => 'https://segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/',
        'schema' => [
            [
                'fieldName' => 'NOME',
                'fieldType' => 'FormTextInput',
                'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f784'
            ]
        ]
    ])
];

echo "1. Payload que será enviado (JSON válido):\n";
echo json_encode($webflow_payload, JSON_PRETTY_PRINT) . "\n\n";

// Simular o JSON malformado que estávamos recebendo (para teste de correção)
$malformed_payload = [
    'triggerType' => 'form_submission',
    'payload' => '{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data":"{"NOME":"LUCIANO TESTE MALFORMADO"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"luciano.malformado@teste.com","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN / MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T21:35:00.000Z","id":"68fa9b5c84d02","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"/","pageUrl":"https: "//segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/"","schema":"[{"fieldName":"NOME"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]"}'
];

echo "2. Payload malformado (como estava chegando antes):\n";
echo json_encode($malformed_payload, JSON_PRETTY_PRINT) . "\n\n";

// Testar o webhook com payload válido
echo "3. Testando webhook com payload VÁLIDO:\n";
testWebhook($webflow_payload, "VÁLIDO");

echo "\n4. Testando webhook com payload MALFORMADO:\n";
testWebhook($malformed_payload, "MALFORMADO");

function testWebhook($payload, $type)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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

    echo "   Tipo: " . $type . "\n";
    echo "   HTTP Code: " . $http_code . "\n";
    if ($error) {
        echo "   cURL Error: " . $error . "\n";
    }
    echo "   Response: " . $response . "\n";

    // Tentar decodificar a resposta
    $response_data = json_decode($response, true);
    if ($response_data) {
        echo "   Status: " . ($response_data['status'] ?? 'unknown') . "\n";
        echo "   Message: " . ($response_data['message'] ?? 'no message') . "\n";
        if (isset($response_data['data']['name'])) {
            echo "   Nome processado: " . $response_data['data']['name'] . "\n";
        }
        if (isset($response_data['data']['email'])) {
            echo "   Email processado: " . $response_data['data']['email'] . "\n";
        }
    }
    echo "\n";
}

echo "=== FIM DO TESTE ===\n";
