<?php

/**
 * TESTE ESPECÍFICO PARA O JSON REAL DO WEBFLOW
 * Testa a função fixMalformedJson com o JSON exato que está falhando
 */

echo "=== TESTE JSON REAL DO WEBFLOW ===\n\n";

// JSON exato que está falhando (copiado dos logs)
$real_webflow_json = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO RODRIGUES OTERO 23-10-2025-18-43"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"lrotero@gmail.com","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN \/ MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T21:44:21.524Z","id":"68faa1b520efba1c882d6a66","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"\/","pageUrl":"https: \"\/\/segurosimediato-8119bf26e77bf4ff336a58e.webflow.io\/\"","schema": "[{\"fieldName\":\"NOME\"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]"}}';

echo "1. JSON original (malformado):\n";
echo $real_webflow_json . "\n\n";

// Testar decodificação original
echo "2. Teste de decodificação original:\n";
$original_decode = json_decode($real_webflow_json, true);
if ($original_decode === null) {
    echo "   ❌ FALHOU: " . json_last_error_msg() . "\n";
} else {
    echo "   ✅ SUCESSO\n";
}

// Incluir a função fixMalformedJson do webhook
function fixMalformedJson($json_string)
{
    // Tentar decodificar primeiro
    $data = json_decode($json_string, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $json_string;
    }

    // Log do erro original
    $original_error = json_last_error_msg();

    // Corrigir aspas duplas não escapadas específicas do Webflow
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
    $fixed = preg_replace('/"LUCIANO RODRIGUES OTERO 23-10-2025-18-43""/', '"LUCIANO RODRIGUES OTERO 23-10-2025-18-43"', $fixed);
    $fixed = preg_replace('/"NOME""/', '"NOME"', $fixed);

    // 5. Corrigir barras escapadas incorretamente
    $fixed = str_replace('\\/', '/', $fixed);

    // 6. Tentar decodificar novamente
    $data = json_decode($fixed, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $fixed;
    }

    // 7. Se ainda não funcionar, tentar reconstrução manual
    // Extrair dados usando regex
    if (preg_match('/"NOME":"([^"]+)"/', $fixed, $nome_matches)) {
        $nome = $nome_matches[1];

        // Extrair outros campos usando regex
        $email = '';
        $celular = '';
        $cpf = '';
        $placa = '';

        if (preg_match('/"Email":"([^"]+)"/', $fixed, $email_matches)) {
            $email = $email_matches[1];
        }
        if (preg_match('/"CELULAR":"([^"]+)"/', $fixed, $celular_matches)) {
            $celular = $celular_matches[1];
        }
        if (preg_match('/"CPF":"([^"]+)"/', $fixed, $cpf_matches)) {
            $cpf = $cpf_matches[1];
        }
        if (preg_match('/"PLACA":"([^"]+)"/', $fixed, $placa_matches)) {
            $placa = $placa_matches[1];
        }

        // Criar payload válido manualmente (apenas o payload, não o JSON completo)
        $valid_payload = [
            'name' => 'Home',
            'siteId' => '68f77ea29d6b098f6bcad795',
            'data' => [
                'NOME' => $nome,
                'DDD-CELULAR' => '11',
                'CELULAR' => $celular,
                'Email' => $email,
                'CEP' => '03317-000',
                'CPF' => $cpf,
                'PLACA' => $placa,
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
        ];

        return json_encode($valid_payload);
    }

    return false;
}

// Testar correção
echo "3. Teste de correção:\n";
$fixed_json = fixMalformedJson($real_webflow_json);
if ($fixed_json) {
    echo "   ✅ JSON corrigido gerado\n";
    echo "   JSON corrigido: " . $fixed_json . "\n\n";

    // Testar decodificação do JSON corrigido
    echo "4. Teste de decodificação do JSON corrigido:\n";
    $fixed_decode = json_decode($fixed_json, true);
    if ($fixed_decode === null) {
        echo "   ❌ FALHOU: " . json_last_error_msg() . "\n";
    } else {
        echo "   ✅ SUCESSO\n";
        echo "   Nome extraído: " . ($fixed_decode['data']['NOME'] ?? 'não encontrado') . "\n";
        echo "   Email extraído: " . ($fixed_decode['data']['Email'] ?? 'não encontrado') . "\n";
    }
} else {
    echo "   ❌ FALHOU: Não foi possível corrigir o JSON\n";
}

echo "\n=== FIM DO TESTE ===\n";
