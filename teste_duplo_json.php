<?php
/**
 * TESTE DUPLO - JSON VÁLIDO vs JSON MALFORMADO
 * Testa o webhook com ambos os formatos para comparar comportamento
 */

echo "=== TESTE DUPLO - JSON VÁLIDO vs JSON MALFORMADO ===\n\n";

// TESTE 1: JSON VÁLIDO conforme documentação do Webflow API V2
$valid_webflow_json = '{"triggerType":"form_submission","payload":"{\"name\":\"Home\",\"siteId\":\"68f77ea29d6b098f6bcad795\",\"data\":{\"NOME\":\"LUCIANO TESTE VÁLIDO 2025-10-23-19-15\",\"DDD-CELULAR\":\"11\",\"CELULAR\":\"97668-7668\",\"Email\":\"luciano.valido@teste.com\",\"CEP\":\"03317-000\",\"CPF\":\"085.546.078-48\",\"PLACA\":\"FPG-8D63\",\"ANO\":\"2016\",\"MARCA\":\"NISSAN / MARCH 16SV\",\"GCLID_FLD\":\"\",\"SEQUENCIA_FLD\":\"\"},\"submittedAt\":\"2025-10-23T22:15:00.000Z\",\"id\":\"68faa7b123456789\",\"formId\":\"68f788bd5dc3f2ca4483eee0\",\"formElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f783\",\"pageId\":\"68f77ea29d6b098f6bcad76f\",\"publishedPath\":\"/\",\"pageUrl\":\"https://segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/\",\"schema\":[{\"fieldName\":\"NOME\",\"fieldType\":\"FormTextInput\",\"fieldElementId\":\"97e5c20e-4fe9-8fcf-d941-485bbc20f784\"}]}"}';

// TESTE 2: JSON MALFORMADO que estamos recebendo do Webflow
$malformed_webflow_json = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO TESTE MALFORMADO 2025-10-23-19-15"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"luciano.malformado@teste.com","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN \/ MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T22:15:00.000Z","id":"68faa7b123456789","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"\/","pageUrl":"https: \"\/\/segurosimediato-8119bf26e77bf4ff336a58e.webflow.io\/\"","schema": "[{\"fieldName\":\"NOME\"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]"}}';

echo "1. TESTE COM JSON VÁLIDO (conforme documentação Webflow):\n";
echo "   JSON: " . substr($valid_webflow_json, 0, 100) . "...\n";
testWebhook($valid_webflow_json, "JSON VÁLIDO");

echo "\n2. TESTE COM JSON MALFORMADO (que estamos recebendo):\n";
echo "   JSON: " . substr($malformed_webflow_json, 0, 100) . "...\n";
testWebhook($malformed_webflow_json, "JSON MALFORMADO");

function testWebhook($json_string, $type)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
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

echo "=== FIM DO TESTE DUPLO ===\n";
?>
