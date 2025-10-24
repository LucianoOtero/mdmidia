<?php
// Teste de correção do JSON malformado do Webflow
function fixWebflowJson($json_string) {
    // Tentar decodificar primeiro
    $data = json_decode($json_string, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $json_string;
    }
    
    // Log do erro original
    $original_error = json_last_error_msg();
    echo "Erro original: " . $original_error . "\n";
    
    // Corrigir aspas duplas não escapadas específicas do Webflow
    $fixed = $json_string;
    
    // Padrão 1: "texto"" -> "texto"
    $fixed = preg_replace('/"([^"]*)""/', '"$1"', $fixed);
    
    // Padrão 2: "https: "//" -> "https://"
    $fixed = preg_replace('/"https: "\//', '"https://', $fixed);
    
    // Padrão 3: "texto""," -> "texto","
    $fixed = preg_replace('/"([^"]*)",/', '"$1",', $fixed);
    
    // Tentar decodificar novamente
    $data = json_decode($fixed, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "JSON corrigido com sucesso!\n";
        return $fixed;
    }
    
    echo "Erro após correção: " . json_last_error_msg() . "\n";
    return false;
}

// Teste com o JSON real do log
$malformed_json = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO RODRIGUES OTERO"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"LROTERO@GMAIL.COM","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN / MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T20:35:21.558Z","id":"68fa918a821543fed64c6a05","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"/","pageUrl":"https: "//segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/"","schema": "[{"fieldName":"NOME"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]}}';

echo "JSON original:\n";
echo $malformed_json . "\n\n";

$fixed_json = fixWebflowJson($malformed_json);
if ($fixed_json) {
    echo "JSON corrigido:\n";
    echo $fixed_json . "\n\n";
    
    // Tentar decodificar o payload também
    $data = json_decode($fixed_json, true);
    if ($data && isset($data['payload'])) {
        $payload_data = json_decode($data['payload'], true);
        if ($payload_data) {
            echo "Payload decodificado com sucesso!\n";
            echo "Nome: " . ($payload_data['data']['NOME'] ?? 'N/A') . "\n";
            echo "Email: " . ($payload_data['data']['Email'] ?? 'N/A') . "\n";
        } else {
            echo "Erro ao decodificar payload: " . json_last_error_msg() . "\n";
        }
    }
}
?>
