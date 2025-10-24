<?php

/**
 * TESTE DIRETO COM JSON REAL DO WEBFLOW
 * Para debugar exatamente o que está acontecendo
 */

echo "=== TESTE DIRETO COM JSON REAL ===\n\n";

// JSON real que está chegando do Webflow (copiado dos logs)
$real_json = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO RODRIGUES OTERO 2025-10-23-19-18"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"lrotero@gmail.com","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN \/ MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T22:19:11.115Z","id":"68faa9dff1f2742693eb2a00","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"\/","pageUrl":"https: \"\/\/segurosimediato-8119bf26e77bf4ff336a58e.webflow.io\/\"","schema": "[{\"fieldName\":\"NOME\"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]"}}';

echo "JSON REAL:\n";
echo substr($real_json, 0, 200) . "...\n\n";

// Testar decodificação
echo "1. TESTE DECODIFICAÇÃO:\n";
$data = json_decode($real_json, true);
echo "Erro: " . json_last_error_msg() . "\n";
echo "Sucesso: " . (json_last_error() === JSON_ERROR_NONE ? 'SIM' : 'NÃO') . "\n\n";

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "2. APLICANDO CORREÇÃO:\n";

    // Aplicar a mesma lógica do webhook
    $fixed = $real_json;

    // Correções específicas
    $fixed = preg_replace('/"Home""/', '"Home"', $fixed);
    $fixed = preg_replace('/"NOME""/', '"NOME"', $fixed);
    $fixed = preg_replace('/"https: "\//', '"https://', $fixed);
    $fixed = str_replace('\\/', '/', $fixed);

    echo "Após correções: " . substr($fixed, 0, 200) . "...\n";

    // Testar decodificação após correções
    $fixed_data = json_decode($fixed, true);
    echo "Erro após correção: " . json_last_error_msg() . "\n";
    echo "Sucesso após correção: " . (json_last_error() === JSON_ERROR_NONE ? 'SIM' : 'NÃO') . "\n\n";

    if (json_last_error() === JSON_ERROR_NONE) {
        echo "3. DADOS EXTRAÍDOS:\n";
        if (isset($fixed_data['payload'])) {
            $payload_data = json_decode($fixed_data['payload'], true);
            if ($payload_data && isset($payload_data['data'])) {
                echo "NOME: " . ($payload_data['data']['NOME'] ?? 'não encontrado') . "\n";
                echo "EMAIL: " . ($payload_data['data']['Email'] ?? 'não encontrado') . "\n";
                echo "CELULAR: " . ($payload_data['data']['CELULAR'] ?? 'não encontrado') . "\n";
            }
        }
    } else {
        echo "3. RECONSTRUÇÃO MANUAL:\n";

        // Extrair dados usando regex
        $nome = '';
        $email = '';
        $celular = '';

        if (preg_match('/"NOME":"([^"]*)"([^,}]*)"([,}])/', $fixed, $matches)) {
            $nome = $matches[1];
        }

        if (preg_match('/"Email":"([^"]*)"([^,}]*)"([,}])/', $fixed, $matches)) {
            $email = $matches[1];
        }

        if (preg_match('/"CELULAR":"([^"]*)"([^,}]*)"([,}])/', $fixed, $matches)) {
            $celular = $matches[1];
        }

        echo "NOME extraído: " . $nome . "\n";
        echo "EMAIL extraído: " . $email . "\n";
        echo "CELULAR extraído: " . $celular . "\n";

        if (!empty($nome) || !empty($email)) {
            echo "\n✅ DADOS EXTRAÍDOS COM SUCESSO!\n";
            echo "✅ Pode criar Lead no EspoCRM\n";
        } else {
            echo "\n❌ FALHA NA EXTRAÇÃO DOS DADOS\n";
        }
    }
}

echo "\n=== FIM DO TESTE ===\n";
