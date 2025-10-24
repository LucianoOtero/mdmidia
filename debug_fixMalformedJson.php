<?php

/**
 * TESTE DE DEBUG DA FUNÇÃO fixMalformedJson
 * Para entender exatamente onde está falhando
 */

echo "=== DEBUG DA FUNÇÃO fixMalformedJson ===\n\n";

// JSON malformado real do Webflow
$malformed_json = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO TESTE MALFORMADO 2025-10-23-19-15"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"luciano.malformado@teste.com","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN \/ MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T22:15:00.000Z","id":"68faa7b123456789","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"\/","pageUrl":"https: \"\/\/segurosimediato-8119bf26e77bf4ff336a58e.webflow.io\/\"","schema": "[{\"fieldName\":\"NOME\"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]"}}';

echo "JSON ORIGINAL:\n";
echo substr($malformed_json, 0, 200) . "...\n\n";

// Testar decodificação original
echo "1. TESTE DECODIFICAÇÃO ORIGINAL:\n";
$original_decode = json_decode($malformed_json, true);
echo "Erro: " . json_last_error_msg() . "\n";
echo "Sucesso: " . (json_last_error() === JSON_ERROR_NONE ? 'SIM' : 'NÃO') . "\n\n";

// Aplicar correções uma por uma
echo "2. APLICANDO CORREÇÕES:\n";

$fixed = $malformed_json;
echo "Original: " . substr($fixed, 0, 100) . "...\n";

// Correção 1: aspas duplas consecutivas
$fixed = preg_replace('/"([^"]*)"([^,}]*)"([,}])/', '"$1"$3', $fixed);
echo "Após correção 1: " . substr($fixed, 0, 100) . "...\n";

// Correção 2: padrões específicos
$fixed = preg_replace('/"Home""/', '"Home"', $fixed);
$fixed = preg_replace('/"NOME""/', '"NOME"', $fixed);
echo "Após correção 2: " . substr($fixed, 0, 100) . "...\n";

// Correção 3: URLs malformadas
$fixed = preg_replace('/"https: "\//', '"https://', $fixed);
echo "Após correção 3: " . substr($fixed, 0, 100) . "...\n";

// Correção 4: barras escapadas
$fixed = str_replace('\\/', '/', $fixed);
echo "Após correção 4: " . substr($fixed, 0, 100) . "...\n\n";

// Testar decodificação após correções
echo "3. TESTE DECODIFICAÇÃO APÓS CORREÇÕES:\n";
$fixed_decode = json_decode($fixed, true);
echo "Erro: " . json_last_error_msg() . "\n";
echo "Sucesso: " . (json_last_error() === JSON_ERROR_NONE ? 'SIM' : 'NÃO') . "\n\n";

// Testar regex de extração
echo "4. TESTE REGEX DE EXTRAÇÃO:\n";
preg_match('/"NOME":"([^"]*)"([^,}]*)"([,}])/', $fixed, $nome_matches);
echo "Regex NOME encontrou: " . (empty($nome_matches) ? 'NÃO' : 'SIM') . "\n";
if (!empty($nome_matches)) {
    echo "Nome extraído: " . $nome_matches[1] . "\n";
}

preg_match('/"Email":"([^"]*)"([^,}]*)"([,}])/', $fixed, $email_matches);
echo "Regex Email encontrou: " . (empty($email_matches) ? 'NÃO' : 'SIM') . "\n";
if (!empty($email_matches)) {
    echo "Email extraído: " . $email_matches[1] . "\n";
}

echo "\n=== FIM DO DEBUG ===\n";
