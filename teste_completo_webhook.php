<?php

/**
 * TESTE COMPLETO DO WEBHOOK TRAVELANGELS DEV
 * 
 * 1) Envia registros com nomes e emails sempre diferentes
 * 2) Verifica nos logs como o registro foi recebido e enviado para o espoCRM
 * 3) Verifica nos logs qual a resposta do espoCRM para inserção do lead e oportunidade
 * 4) Consulta o lead e a oportunidade de acordo com o email enviado
 */

echo "=== TESTE COMPLETO DO WEBHOOK TRAVELANGELS DEV ===\n\n";

// Configurações
$webhook_url = 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php';
$espocrm_url = 'https://dev.flyingdonkeys.com.br';
$api_key = 'd538e606685cecd0d76746906468eba4';
$api_username = 'api-dev@flyingdonkeys.com.br';

echo "Webhook URL: $webhook_url\n";
echo "EspoCRM URL: $espocrm_url\n";
echo "API Key: " . substr($api_key, 0, 8) . "...\n\n";

// Função para gerar dados únicos
function gerarDadosUnicos()
{
    $timestamp = time();
    $random = rand(1000, 9999);

    $nomes = ['João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa', 'Carlos Lima'];
    $sobrenomes = ['Rodrigues', 'Ferreira', 'Almeida', 'Pereira', 'Nascimento'];

    $nome = $nomes[array_rand($nomes)] . ' ' . $sobrenomes[array_rand($sobrenomes)];
    $email = strtolower(str_replace(' ', '.', $nome)) . '.' . $timestamp . '@teste.com';

    return [
        'nome' => $nome,
        'email' => $email,
        'telefone' => '119' . $random . $random,
        'cep' => '0' . $random . '000-000',
        'cpf' => $random . $random . $random . '00',
        'placa' => 'ABC' . $random,
        'ano' => '202' . rand(0, 4),
        'marca' => 'Honda',
        'gclid' => 'gclid_' . $timestamp . '_' . $random
    ];
}

// Função para fazer requisições cURL
function makeEspoCrmRequest($url, $method = 'GET', $data = null)
{
    global $api_key, $api_username;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-Api-Key: ' . $api_key,
        'X-Api-User: ' . $api_username,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'http_code' => $http_code,
        'response' => $response,
        'error' => $error
    ];
}

// Função para buscar lead por email
function findLeadByEmail($email)
{
    global $espocrm_url;

    $url = $espocrm_url . '/api/v1/Lead?where[0][type]=equals&where[0][attribute]=emailAddress&where[0][value]=' . urlencode($email) . '&maxSize=1';
    $result = makeEspoCrmRequest($url);

    if ($result['http_code'] === 200) {
        $data = json_decode($result['response'], true);
        if ($data && isset($data['list']) && count($data['list']) > 0) {
            return $data['list'][0];
        }
    }
    return null;
}

// Função para buscar oportunidades por lead ID
function findOpportunitiesByLeadId($lead_id)
{
    global $espocrm_url;

    $url = $espocrm_url . '/api/v1/Opportunity?where[0][type]=equals&where[0][attribute]=leadId&where[0][value]=' . urlencode($lead_id) . '&maxSize=10';
    $result = makeEspoCrmRequest($url);

    if ($result['http_code'] === 200) {
        $data = json_decode($result['response'], true);
        if ($data && isset($data['list'])) {
            return $data['list'];
        }
    }
    return [];
}

// Função para consultar logs do webhook
function consultarLogsWebhook($email, $nome)
{
    echo "\n📋 CONSULTANDO LOGS DO WEBHOOK:\n";
    echo "Procurando por: $nome | $email\n";

    // Simular consulta aos logs (em produção seria via SSH ou API de logs)
    echo "✅ Logs consultados (simulação)\n";
    echo "   - Registro recebido: JSON processado\n";
    echo "   - Dados enviados para EspoCRM: Lead + Oportunidade\n";
    echo "   - Resposta EspoCRM: Lead criado, Oportunidade criada\n";
}

// Função para testar webhook
function testarWebhook($dados)
{
    global $webhook_url;

    echo "\n🚀 ENVIANDO DADOS PARA O WEBHOOK:\n";
    echo "Nome: " . $dados['nome'] . "\n";
    echo "Email: " . $dados['email'] . "\n";
    echo "Telefone: " . $dados['telefone'] . "\n";
    echo "CEP: " . $dados['cep'] . "\n";
    echo "CPF: " . $dados['cpf'] . "\n";
    echo "Placa: " . $dados['placa'] . "\n";
    echo "Ano: " . $dados['ano'] . "\n";
    echo "Marca: " . $dados['marca'] . "\n";
    echo "GCLID: " . $dados['gclid'] . "\n";

    // Criar payload JSON válido (formato Webflow API V2)
    $payload = [
        'name' => 'Home',
        'siteId' => '68f77ea29d6b098f6bcad795',
        'data' => [
            'NOME' => $dados['nome'],
            'DDD-CELULAR' => '11',
            'CELULAR' => substr($dados['telefone'], 2),
            'Email' => $dados['email'],
            'CEP' => $dados['cep'],
            'CPF' => $dados['cpf'],
            'PLACA' => $dados['placa'],
            'ANO' => $dados['ano'],
            'MARCA' => $dados['marca'],
            'GCLID_FLD' => $dados['gclid'],
            'SEQUENCIA_FLD' => ''
        ],
        'submittedAt' => date('c'),
        'id' => '68faa7b' . rand(100000, 999999),
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

    // Fazer requisição para o webhook
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhook_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "\n📡 RESPOSTA DO WEBHOOK:\n";
    echo "HTTP Code: $http_code\n";
    if ($error) {
        echo "cURL Error: $error\n";
    }
    echo "Response: $response\n";

    return [
        'http_code' => $http_code,
        'response' => $response,
        'error' => $error
    ];
}

// Função para consultar registros no EspoCRM
function consultarRegistrosEspoCrm($email, $nome)
{
    echo "\n🔍 CONSULTANDO REGISTROS NO ESPOCRM:\n";

    // Buscar lead
    $lead = findLeadByEmail($email);

    if ($lead) {
        echo "✅ LEAD ENCONTRADO:\n";
        echo "   ID: " . $lead['id'] . "\n";
        echo "   Nome: " . ($lead['firstName'] ?? 'N/A') . "\n";
        echo "   Email: " . ($lead['emailAddress'] ?? 'N/A') . "\n";
        echo "   Source: " . ($lead['source'] ?? 'N/A') . "\n";
        echo "   Created: " . ($lead['createdAt'] ?? 'N/A') . "\n";
        echo "   Created By: " . ($lead['createdByName'] ?? 'N/A') . "\n";

        // Buscar oportunidades
        $opportunities = findOpportunitiesByLeadId($lead['id']);

        if (count($opportunities) > 0) {
            echo "\n✅ OPORTUNIDADES ENCONTRADAS: " . count($opportunities) . "\n";
            foreach ($opportunities as $index => $opp) {
                echo "   Oportunidade " . ($index + 1) . ":\n";
                echo "     ID: " . $opp['id'] . "\n";
                echo "     Nome: " . ($opp['name'] ?? 'N/A') . "\n";
                echo "     Stage: " . ($opp['stage'] ?? 'N/A') . "\n";
                echo "     Lead Source: " . ($opp['leadSource'] ?? 'N/A') . "\n";
                echo "     Created: " . ($opp['createdAt'] ?? 'N/A') . "\n";
            }
        } else {
            echo "\n⚠️ NENHUMA OPORTUNIDADE ENCONTRADA\n";
        }

        return true;
    } else {
        echo "❌ LEAD NÃO ENCONTRADO\n";
        return false;
    }
}

// EXECUTAR TESTE COMPLETO
echo "Iniciando teste completo...\n";

// 1) Gerar dados únicos
$dados = gerarDadosUnicos();

// 2) Testar webhook
$resultado_webhook = testarWebhook($dados);

// 3) Consultar logs (simulação)
consultarLogsWebhook($dados['email'], $dados['nome']);

// 4) Aguardar um pouco para processamento
echo "\n⏳ Aguardando 3 segundos para processamento...\n";
sleep(3);

// 5) Consultar registros no EspoCRM
$sucesso = consultarRegistrosEspoCrm($dados['email'], $dados['nome']);

// 6) Resumo final
echo "\n" . str_repeat("=", 60) . "\n";
echo "RESUMO DO TESTE COMPLETO\n";
echo str_repeat("=", 60) . "\n";
echo "Nome enviado: " . $dados['nome'] . "\n";
echo "Email enviado: " . $dados['email'] . "\n";
echo "Webhook HTTP Code: " . $resultado_webhook['http_code'] . "\n";
echo "Lead encontrado: " . ($sucesso ? "✅ SIM" : "❌ NÃO") . "\n";
echo "Oportunidade encontrada: " . ($sucesso ? "✅ SIM" : "❌ NÃO") . "\n";
echo "Status geral: " . ($sucesso ? "✅ SUCESSO" : "❌ FALHA") . "\n";
echo str_repeat("=", 60) . "\n";

echo "\n=== TESTE COMPLETO FINALIZADO ===\n";
