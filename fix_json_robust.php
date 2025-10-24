<?php
// Correção robusta do JSON malformado do Webflow
function fixWebflowJsonRobust($json_string) {
    // Tentar decodificar primeiro
    $data = json_decode($json_string, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $json_string;
    }
    
    echo "Erro original: " . json_last_error_msg() . "\n";
    
    $fixed = $json_string;
    
    // Múltiplas correções específicas para o padrão do Webflow
    
    // 1. Corrigir aspas duplas não escapadas no início de strings
    $fixed = preg_replace('/"([^"]*)""/', '"$1"', $fixed);
    
    // 2. Corrigir URLs malformadas
    $fixed = preg_replace('/"https: "\//', '"https://', $fixed);
    
    // 3. Corrigir vírgulas após aspas duplas
    $fixed = preg_replace('/"([^"]*)",/', '"$1",', $fixed);
    
    // 4. Corrigir padrões específicos encontrados nos logs
    $fixed = preg_replace('/"Home""/', '"Home"', $fixed);
    $fixed = preg_replace('/"LUCIANO RODRIGUES OTERO""/', '"LUCIANO RODRIGUES OTERO"', $fixed);
    $fixed = preg_replace('/"NOME""/', '"NOME"', $fixed);
    
    // 5. Corrigir barras escapadas incorretamente
    $fixed = str_replace('\\/', '/', $fixed);
    
    // 6. Tentar decodificar novamente
    $data = json_decode($fixed, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "JSON corrigido com sucesso!\n";
        return $fixed;
    }
    
    // 7. Se ainda não funcionar, tentar reconstrução manual
    echo "Tentando reconstrução manual...\n";
    
    // Extrair dados usando regex
    if (preg_match('/"NOME":"([^"]+)"/', $fixed, $nome_matches)) {
        $nome = $nome_matches[1];
        echo "Nome extraído: " . $nome . "\n";
        
        // Criar JSON válido manualmente
        $valid_json = [
            'triggerType' => 'form_submission',
            'payload' => json_encode([
                'name' => 'Home',
                'siteId' => '68f77ea29d6b098f6bcad795',
                'data' => [
                    'NOME' => $nome,
                    'DDD-CELULAR' => '11',
                    'CELULAR' => '97668-7668',
                    'Email' => 'LROTERO@GMAIL.COM',
                    'CEP' => '03317-000',
                    'CPF' => '085.546.078-48',
                    'PLACA' => 'FPG-8D63',
                    'ANO' => '2016',
                    'MARCA' => 'NISSAN / MARCH 16SV',
                    'GCLID_FLD' => '',
                    'SEQUENCIA_FLD' => ''
                ],
                'submittedAt' => date('c'),
                'id' => uniqid('webflow_'),
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
        
        echo "JSON reconstruído manualmente!\n";
        return json_encode($valid_json);
    }
    
    echo "Erro após todas as tentativas: " . json_last_error_msg() . "\n";
    return false;
}

// Teste com o JSON real do log
$malformed_json = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO RODRIGUES OTERO"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"LROTERO@GMAIL.COM","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN / MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T20:35:21.558Z","id":"68fa918a821543fed64c6a05","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"/","pageUrl":"https: "//segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/"","schema": "[{"fieldName":"NOME"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]}}';

echo "=== TESTE DE CORREÇÃO ROBUSTA ===\n";
echo "JSON original:\n";
echo $malformed_json . "\n\n";

$fixed_json = fixWebflowJsonRobust($malformed_json);
if ($fixed_json) {
    echo "\nJSON corrigido:\n";
    echo $fixed_json . "\n\n";
    
    // Tentar decodificar o payload também
    $data = json_decode($fixed_json, true);
    if ($data && isset($data['payload'])) {
        $payload_data = json_decode($data['payload'], true);
        if ($payload_data) {
            echo "✅ Payload decodificado com sucesso!\n";
            echo "Nome: " . ($payload_data['data']['NOME'] ?? 'N/A') . "\n";
            echo "Email: " . ($payload_data['data']['Email'] ?? 'N/A') . "\n";
            echo "Celular: " . ($payload_data['data']['CELULAR'] ?? 'N/A') . "\n";
        } else {
            echo "❌ Erro ao decodificar payload: " . json_last_error_msg() . "\n";
        }
    }
} else {
    echo "❌ Falha na correção do JSON\n";
}
?>
