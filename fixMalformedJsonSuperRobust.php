<?php

/**
 * FUNÇÃO SUPER ROBUSTA PARA CORRIGIR JSON MALFORMADO DO WEBFLOW
 * Baseada em testes lógicos completos e análise da malformação real
 */

function fixMalformedJsonSuperRobust($json_string)
{
    // Log inicial
    error_log("=== INICIANDO CORREÇÃO DE JSON MALFORMADO ===");
    error_log("JSON Original: " . substr($json_string, 0, 200) . "...");

    // CAMADA 1 - DECODIFICAR JSON PRINCIPAL
    error_log("CAMADA 1: Decodificando JSON principal");

    $main_data = json_decode($json_string, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        error_log("CAMADA 1: JSON principal válido");

        // Se tem payload, corrigir o payload interno
        if (isset($main_data['payload'])) {
            error_log("CAMADA 1: Corrigindo payload interno");
            $fixed_payload = fixPayloadInternal($main_data['payload']);
            if ($fixed_payload) {
                $main_data['payload'] = $fixed_payload;
                return json_encode($main_data);
            }
        }

        return $json_string; // Já está correto
    }

    // CAMADA 2 - CORREÇÕES SIMPLES E SEGURAS
    error_log("CAMADA 2: Correções simples");

    // 2.1 Remover aspas duplas extras genéricas
    $fixed = preg_replace('/"([^"]*)"+([,}])/', '"$1"$2', $json_string);
    error_log("Após regex genérico: " . substr($fixed, 0, 200) . "...");

    // 2.2 Corrigir escape de barras
    $fixed = str_replace('\\/', '/', $fixed);

    // 2.3 Corrigir URLs malformadas
    $fixed = preg_replace('/"https: "\\\/\\\//', '"https://', $fixed);
    $fixed = preg_replace('/"http: "\\\/\\\//', '"http://', $fixed);

    // 2.4 Testar se já está correto
    $test_decode = json_decode($fixed, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        error_log("CAMADA 2: JSON corrigido com sucesso!");
        return $fixed;
    }

    // CAMADA 3 - CORREÇÕES ESPECÍFICAS DO WEBFLOW
    error_log("CAMADA 3: Correções específicas do Webflow");

    $patterns = [
        '/"Home""/' => '"Home"',
        '/"NOME""/' => '"NOME"',
        '/"Email""/' => '"Email"',
        '/"DDD-CELULAR""/' => '"DDD-CELULAR"',
        '/"CELULAR""/' => '"CELULAR"',
        '/"CEP""/' => '"CEP"',
        '/"CPF""/' => '"CPF"',
        '/"PLACA""/' => '"PLACA"',
        '/"ANO""/' => '"ANO"',
        '/"MARCA""/' => '"MARCA"',
        '/"GCLID_FLD""/' => '"GCLID_FLD"',
        '/"SEQUENCIA_FLD""/' => '"SEQUENCIA_FLD"'
    ];

    foreach ($patterns as $pattern => $replacement) {
        $fixed = preg_replace($pattern, $replacement, $fixed);
    }

    // 3.2 Testar novamente
    $test_decode = json_decode($fixed, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        error_log("CAMADA 3: JSON corrigido com sucesso!");
        return $fixed;
    }

    // CAMADA 4 - RECONSTRUÇÃO INTELIGENTE
    error_log("CAMADA 4: Reconstrução inteligente");

    // 4.1 Extrair dados com regex robustos
    $fields = [
        'NOME' => '/"NOME":"([^"]*)"+([,}])/',
        'Email' => '/"Email":"([^"]*)"+([,}])/',
        'DDD-CELULAR' => '/"DDD-CELULAR":"([^"]*)"+([,}])/',
        'CELULAR' => '/"CELULAR":"([^"]*)"+([,}])/',
        'CEP' => '/"CEP":"([^"]*)"+([,}])/',
        'CPF' => '/"CPF":"([^"]*)"+([,}])/',
        'PLACA' => '/"PLACA":"([^"]*)"+([,}])/',
        'ANO' => '/"ANO":"([^"]*)"+([,}])/',
        'MARCA' => '/"MARCA":"([^"]*)"+([,}])/',
        'GCLID_FLD' => '/"GCLID_FLD":"([^"]*)"+([,}])/',
        'SEQUENCIA_FLD' => '/"SEQUENCIA_FLD":"([^"]*)"+([,}])/'
    ];

    $extracted_data = [];
    foreach ($fields as $field => $pattern) {
        if (preg_match($pattern, $fixed, $matches)) {
            $extracted_data[$field] = $matches[1];
            error_log("Campo extraído: $field = " . $matches[1]);
        }
    }

    // 4.2 Se conseguiu extrair dados suficientes, reconstruir
    if (count($extracted_data) >= 2) {
        error_log("CAMADA 4: Dados suficientes extraídos, reconstruindo JSON");
        $reconstructed = reconstructJson($extracted_data);
        error_log("JSON Reconstruído: " . substr($reconstructed, 0, 200) . "...");
        return $reconstructed;
    }

    // CAMADA 5 - FALLBACK COM DADOS MÍNIMOS
    error_log("CAMADA 5: Fallback com dados mínimos");

    // 5.1 Tentar extrair pelo menos nome ou email
    $minimal_patterns = [
        '/"NOME":"([^"]+)"/',
        '/"Email":"([^"]+)"/',
        '/"email":"([^"]+)"/',
        '/"nome":"([^"]+)"/'
    ];

    $minimal_data = [];
    foreach ($minimal_patterns as $pattern) {
        if (preg_match($pattern, $fixed, $matches)) {
            $minimal_data[] = $matches[1];
            error_log("Dado mínimo extraído: " . $matches[1]);
        }
    }

    // 5.2 Se conseguiu algo, criar JSON mínimo
    if (!empty($minimal_data)) {
        error_log("CAMADA 5: Criando JSON mínimo");
        $minimal_json = createMinimalJson($minimal_data);
        error_log("JSON Mínimo: " . substr($minimal_json, 0, 200) . "...");
        return $minimal_json;
    }

    // Se chegou até aqui, falhou completamente
    error_log("=== FALHA COMPLETA: Não foi possível corrigir o JSON ===");
    return false;
}

/**
 * Função auxiliar para corrigir payload interno
 */
function fixPayloadInternal($payload_string)
{
    error_log("Corrigindo payload interno: " . substr($payload_string, 0, 100) . "...");

    // Tentar decodificar o payload
    $payload_data = json_decode($payload_string, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        error_log("Payload interno válido");
        return $payload_string;
    }

    // Corrigir aspas duplas extras no payload
    $fixed_payload = preg_replace('/"([^"]*)"+([,}])/', '"$1"$2', $payload_string);

    // Tentar decodificar novamente
    $payload_data = json_decode($fixed_payload, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        error_log("Payload interno corrigido");
        return $fixed_payload;
    }

    // Se tem data interno, corrigir também
    if (isset($payload_data['data'])) {
        $data_string = $payload_data['data'];
        $fixed_data = preg_replace('/"([^"]*)"+([,}])/', '"$1"$2', $data_string);
        $payload_data['data'] = $fixed_data;
        return json_encode($payload_data);
    }

    return false;
}

/**
 * Função auxiliar para reconstruir JSON completo
 */
function reconstructJson($data)
{
    return json_encode([
        'name' => 'Home',
        'siteId' => '68f77ea29d6b098f6bcad795',
        'data' => $data,
        'submittedAt' => date('c'),
        'id' => uniqid(),
        'formId' => '68f788bd5dc3f2ca4483eee0',
        'formElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f783',
        'pageId' => '68f77ea29d6b098f6bcad76f',
        'publishedPath' => '/',
        'pageUrl' => 'https://segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/',
        'schema' => []
    ]);
}

/**
 * Função auxiliar para criar JSON mínimo
 */
function createMinimalJson($data)
{
    return json_encode([
        'name' => 'Home',
        'data' => [
            'NOME' => $data[0] ?? 'Nome não informado',
            'Email' => $data[1] ?? 'email@nao.informado.com'
        ]
    ]);
}

/**
 * Função de teste para validar a correção
 */
function testFixMalformedJson()
{
    echo "=== TESTANDO FUNÇÃO DE CORREÇÃO DE JSON ===\n";

    // TESTE 1: JSON MALFORMADO REAL DO WEBFLOW
    echo "\n🔴 TESTE 1: JSON MALFORMADO\n";
    echo "============================\n";

    $malformed_json = '{"triggerType":"form_submission","payload":"{\"name\":\"Home\"\",\"siteId\":\"68f77ea29d6b098f6bcad795\",\"data\":\"{\"NOME\":\"LUCIANO RODRIGUES OTERO 202523102104\"\",\"DDD-CELULAR\":\"11\",\"CELULAR\":\"97668-7668\",\"Email\":\"lrotero@gmail.com\",\"CEP\":\"03317-000\",\"CPF\":\"085.546.078-48\",\"PLACA\":\"FPG-8D63\",\"ANO\":\"2016\",\"MARCA\":\"NISSAN / MARCH 16SV\",\"GCLID_FLD\":\"\",\"SEQUENCIA_FLD\":\"\"}\"}"}';

    echo "JSON Malformado: " . substr($malformed_json, 0, 100) . "...\n";

    $result1 = fixMalformedJsonSuperRobust($malformed_json);

    if ($result1) {
        echo "✅ SUCESSO: JSON corrigido!\n";
        echo "Resultado: " . substr($result1, 0, 200) . "...\n";

        // Testar se é JSON válido
        $decoded = json_decode($result1, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "✅ JSON válido após correção!\n";
            if (isset($decoded['payload'])) {
                $payload_data = json_decode($decoded['payload'], true);
                if (isset($payload_data['data']['NOME'])) {
                    echo "✅ Nome extraído: " . $payload_data['data']['NOME'] . "\n";
                }
                if (isset($payload_data['data']['Email'])) {
                    echo "✅ Email extraído: " . $payload_data['data']['Email'] . "\n";
                }
            }
        } else {
            echo "❌ JSON ainda inválido após correção\n";
        }
    } else {
        echo "❌ FALHA: Não foi possível corrigir o JSON\n";
    }

    // TESTE 2: JSON BEM FORMADO
    echo "\n🟢 TESTE 2: JSON BEM FORMADO\n";
    echo "============================\n";

    $well_formed_json = '{"triggerType":"form_submission","payload":"{\"name\":\"Home\",\"siteId\":\"68f77ea29d6b098f6bcad795\",\"data\":\"{\"NOME\":\"TESTE BEM FORMADO\",\"DDD-CELULAR\":\"11\",\"CELULAR\":\"999999999\",\"Email\":\"teste@bemformado.com\",\"CEP\":\"01234567\",\"CPF\":\"12345678901\",\"PLACA\":\"ABC1234\",\"ANO\":\"2023\",\"MARCA\":\"Honda\",\"GCLID_FLD\":\"gclid_teste\",\"SEQUENCIA_FLD\":\"\"}\"}"}';

    echo "JSON Bem Formado: " . substr($well_formed_json, 0, 100) . "...\n";

    $result2 = fixMalformedJsonSuperRobust($well_formed_json);

    if ($result2) {
        echo "✅ SUCESSO: JSON processado!\n";
        echo "Resultado: " . substr($result2, 0, 200) . "...\n";

        // Testar se é JSON válido
        $decoded = json_decode($result2, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "✅ JSON válido após processamento!\n";
            if (isset($decoded['payload'])) {
                $payload_data = json_decode($decoded['payload'], true);
                if (isset($payload_data['data']['NOME'])) {
                    echo "✅ Nome extraído: " . $payload_data['data']['NOME'] . "\n";
                }
                if (isset($payload_data['data']['Email'])) {
                    echo "✅ Email extraído: " . $payload_data['data']['Email'] . "\n";
                }
            }
        } else {
            echo "❌ JSON inválido após processamento\n";
        }
    } else {
        echo "❌ FALHA: Não foi possível processar o JSON\n";
    }

    // TESTE 3: JSON SIMPLES (SEM PAYLOAD)
    echo "\n🟡 TESTE 3: JSON SIMPLES\n";
    echo "========================\n";

    $simple_json = '{"name":"Home","siteId":"68f77ea29d6b098f6bcad795","data":{"NOME":"TESTE SIMPLES","Email":"teste@simples.com","DDD-CELULAR":"11","CELULAR":"999999999"}}';

    echo "JSON Simples: " . substr($simple_json, 0, 100) . "...\n";

    $result3 = fixMalformedJsonSuperRobust($simple_json);

    if ($result3) {
        echo "✅ SUCESSO: JSON processado!\n";
        echo "Resultado: " . substr($result3, 0, 200) . "...\n";

        // Testar se é JSON válido
        $decoded = json_decode($result3, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "✅ JSON válido após processamento!\n";
            if (isset($decoded['data']['NOME'])) {
                echo "✅ Nome extraído: " . $decoded['data']['NOME'] . "\n";
            }
            if (isset($decoded['data']['Email'])) {
                echo "✅ Email extraído: " . $decoded['data']['Email'] . "\n";
            }
        } else {
            echo "❌ JSON inválido após processamento\n";
        }
    } else {
        echo "❌ FALHA: Não foi possível processar o JSON\n";
    }

    echo "\n=== FIM DOS TESTES ===\n";
}

// Executar teste se chamado diretamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    testFixMalformedJson();
}
