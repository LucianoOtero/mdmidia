<?php
/**
 * TESTE DETALHADO DO REGEX
 * Analisa exatamente qual padrão precisa ser corrigido
 */

echo "=== TESTE DETALHADO DO REGEX ===\n\n";

// JSON real problemático
$problematic_json = '{"triggerType":"form_submission","payload": "{"name":"Home"","siteId":"68f77ea29d6b098f6bcad795","data": "{"NOME":"LUCIANO RODRIGUES OTERO 2025-10-23-19-07"","DDD-CELULAR":"11","CELULAR":"97668-7668","Email":"lrotero@gmail.com","CEP":"03317-000","CPF":"085.546.078-48","PLACA":"FPG-8D63","ANO":"2016","MARCA":"NISSAN \/ MARCH 16SV","GCLID_FLD":"","SEQUENCIA_FLD":""},"submittedAt":"2025-10-23T22:07:47.289Z","id":"68faa7333bd2639ebc257c64","formId":"68f788bd5dc3f2ca4483eee0","formElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f783","pageId":"68f77ea29d6b098f6bcad76f","publishedPath":"\/","pageUrl":"https: \"\/\/segurosimediato-8119bf26e77bf4ff336a58e.webflow.io\/\"","schema": "[{\"fieldName\":\"NOME\"","fieldType":"FormTextInput","fieldElementId":"97e5c20e-4fe9-8fcf-d941-485bbc20f784"}]"}}';

echo "1. Analisando padrões problemáticos:\n";

// Encontrar todos os padrões problemáticos
preg_match_all('/"([^"]*)"([^,}]*)"([,}])/', $problematic_json, $matches, PREG_SET_ORDER);

echo "   Padrões encontrados pelo regex:\n";
foreach ($matches as $i => $match) {
    echo "   " . ($i+1) . ". '" . $match[0] . "' -> '" . $match[1] . "' + '" . $match[2] . "' + '" . $match[3] . "'\n";
}

echo "\n2. Testando correções específicas:\n";

$fixed = $problematic_json;

// Teste 1: Regex original (hardcoded)
echo "   Teste 1 - Regex hardcoded:\n";
$test1 = preg_replace('/"LUCIANO RODRIGUES OTERO""/', '"LUCIANO RODRIGUES OTERO"', $fixed);
echo "   Resultado: " . (strpos($test1, 'LUCIANO RODRIGUES OTERO""') === false ? "✅ CORRIGIDO" : "❌ NÃO CORRIGIDO") . "\n";

// Teste 2: Regex genérico atual
echo "   Teste 2 - Regex genérico atual:\n";
$test2 = preg_replace('/"([^"]*)"([^,}]*)"([,}])/', '"$1"$3', $fixed);
echo "   Resultado: " . (strpos($test2, 'LUCIANO RODRIGUES OTERO 2025-10-23-19-07""') === false ? "✅ CORRIGIDO" : "❌ NÃO CORRIGIDO") . "\n";

// Teste 3: Regex mais específico
echo "   Teste 3 - Regex mais específico:\n";
$test3 = preg_replace('/"([^"]*)"([^,}]*)"([,}])/', '"$1"$3', $fixed);
echo "   Resultado: " . (strpos($test3, 'LUCIANO RODRIGUES OTERO 2025-10-23-19-07""') === false ? "✅ CORRIGIDO" : "❌ NÃO CORRIGIDO") . "\n";

// Teste 4: Regex específico para o padrão exato
echo "   Teste 4 - Regex específico para padrão exato:\n";
$test4 = preg_replace('/"([^"]*)"([^,}]*)"([,}])/', '"$1"$3', $fixed);
echo "   Resultado: " . (strpos($test4, 'LUCIANO RODRIGUES OTERO 2025-10-23-19-07""') === false ? "✅ CORRIGIDO" : "❌ NÃO CORRIGIDO") . "\n";

echo "\n3. Analisando o padrão exato:\n";
echo "   Padrão problemático: 'LUCIANO RODRIGUES OTERO 2025-10-23-19-07\"\"'\n";
echo "   Deveria ser: 'LUCIANO RODRIGUES OTERO 2025-10-23-19-07\"'\n";

// Teste 5: Regex específico para aspas duplas no final
echo "\n4. Teste 5 - Regex para aspas duplas no final:\n";
$test5 = preg_replace('/"([^"]*)"([^,}]*)"([,}])/', '"$1"$3', $fixed);
echo "   Resultado: " . (strpos($test5, 'LUCIANO RODRIGUES OTERO 2025-10-23-19-07""') === false ? "✅ CORRIGIDO" : "❌ NÃO CORRIGIDO") . "\n";

// Teste 6: Regex mais simples
echo "\n5. Teste 6 - Regex mais simples:\n";
$test6 = preg_replace('/"([^"]*)"([^,}]*)"([,}])/', '"$1"$3', $fixed);
echo "   Resultado: " . (strpos($test6, 'LUCIANO RODRIGUES OTERO 2025-10-23-19-07""') === false ? "✅ CORRIGIDO" : "❌ NÃO CORRIGIDO") . "\n";

echo "\n=== FIM DO TESTE ===\n";
?>
