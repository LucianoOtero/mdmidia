<?php

/**
 * TESTE COMPLETO E ELEGANTE DO WEBHOOK TRAVELANGELS DEV
 * 
 * FUNCIONALIDADES:
 * 1) Envia registros com nomes e emails sempre diferentes
 * 2) Consulta LOGS REAIS do servidor bpsegurosimediato.com.br
 * 3) Verifica LOGS REAIS das respostas do EspoCRM para inserção do lead e oportunidade
 * 4) Consulta o lead e a oportunidade de acordo com o email enviado
 * 5) Apresenta relatório completo e elegante de tudo
 * 
 * TUDO EXECUTADO LOCALMENTE NO WINDOWS - SEM PRECISAR CONSULTAR SERVIDOR MANUALMENTE
 */

echo "=== TESTE COMPLETO E ELEGANTE DO WEBHOOK TRAVELANGELS DEV ===\n\n";

// Configurações
$webhook_url = 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php';
$espocrm_url = 'https://dev.flyingdonkeys.com.br';
$api_key = 'd538e606685cecd0d76746906468eba4';
$api_username = 'api-dev@flyingdonkeys.com.br';
$server_host = 'bpsegurosimediato.com.br';
$server_user = 'root';

echo "🔧 CONFIGURAÇÕES:\n";
echo "   Webhook URL: $webhook_url\n";
echo "   EspoCRM URL: $espocrm_url\n";
echo "   Server: $server_host\n";
echo "   API Key: " . substr($api_key, 0, 8) . "...\n\n";

// Função para gerar dados únicos e elegantes
function gerarDadosUnicos()
{
    $timestamp = time();
    $random = rand(1000, 9999);

    $nomes = ['João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa', 'Carlos Lima', 'Fernanda Lima', 'Ricardo Souza', 'Juliana Costa'];
    $sobrenomes = ['Rodrigues', 'Ferreira', 'Almeida', 'Pereira', 'Nascimento', 'Santos', 'Oliveira', 'Silva'];

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
        'gclid' => 'gclid_' . $timestamp . '_' . $random,
        'timestamp' => $timestamp
    ];
}

// Função para executar comandos SSH no servidor
function executarSSH($comando)
{
    global $server_host, $server_user;

    $ssh_command = "ssh $server_user@$server_host \"$comando\"";

    $output = [];
    $return_code = 0;

    exec($ssh_command, $output, $return_code);

    return [
        'output' => $output,
        'return_code' => $return_code,
        'command' => $ssh_command
    ];
}

// Função para consultar logs reais do webhook
function consultarLogsReais($email, $nome, $timestamp)
{
    echo "\n📋 CONSULTANDO LOGS REAIS DO WEBHOOK:\n";
    echo "   Procurando por: $nome | $email\n";
    echo "   Timestamp: $timestamp\n";

    // Consultar logs do webhook travelangels_dev
    $log_file = '/var/www/html/dev/logs/travelangels_dev.txt';
    $comando_logs = "tail -50 $log_file | grep -E \"($nome|$email|$timestamp)\" || echo 'Nenhum log encontrado'";

    $resultado_logs = executarSSH($comando_logs);

    echo "   📁 Arquivo de log: $log_file\n";
    echo "   🔍 Comando executado: " . $resultado_logs['command'] . "\n";
    echo "   📊 Resultado:\n";

    if (!empty($resultado_logs['output'])) {
        foreach ($resultado_logs['output'] as $linha) {
            echo "      $linha\n";
        }
    } else {
        echo "      ❌ Nenhum log encontrado\n";
    }

    // Consultar logs gerais do sistema
    $comando_system_logs = "tail -20 /var/log/nginx/error.log | grep -E \"($nome|$email|travelangels)\" || echo 'Nenhum erro encontrado'";
    $resultado_system = executarSSH($comando_system_logs);

    echo "\n   🔍 Logs do sistema (Nginx):\n";
    if (!empty($resultado_system['output'])) {
        foreach ($resultado_system['output'] as $linha) {
            echo "      $linha\n";
        }
    } else {
        echo "      ✅ Nenhum erro encontrado\n";
    }

    return $resultado_logs;
}

// Função para fazer requisições cURL para EspoCRM
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

// Função para testar webhook com payload completo
function testarWebhookCompleto($dados)
{
    global $webhook_url;

    echo "\n🚀 ENVIANDO DADOS PARA O WEBHOOK:\n";
    echo "   Nome: " . $dados['nome'] . "\n";
    echo "   Email: " . $dados['email'] . "\n";
    echo "   Telefone: " . $dados['telefone'] . "\n";
    echo "   CEP: " . $dados['cep'] . "\n";
    echo "   CPF: " . $dados['cpf'] . "\n";
    echo "   Placa: " . $dados['placa'] . "\n";
    echo "   Ano: " . $dados['ano'] . "\n";
    echo "   Marca: " . $dados['marca'] . "\n";
    echo "   GCLID: " . $dados['gclid'] . "\n";

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
    echo "   HTTP Code: $http_code\n";
    if ($error) {
        echo "   cURL Error: $error\n";
    }
    echo "   Response: $response\n";

    return [
        'http_code' => $http_code,
        'response' => $response,
        'error' => $error,
        'payload' => $payload
    ];
}

// Função para consultar registros no EspoCRM com detalhes completos
function consultarRegistrosEspoCrmCompleto($email, $nome)
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
        echo "   Modified: " . ($lead['modifiedAt'] ?? 'N/A') . "\n";

        // Buscar oportunidades
        $opportunities = findOpportunitiesByLeadId($lead['id']);

        if (count($opportunities) > 0) {
            echo "\n✅ OPORTUNIDADES ENCONTRADAS: " . count($opportunities) . "\n";
            foreach ($opportunities as $index => $opp) {
                echo "   Oportunidade " . ($index + 1) . ":\n";
                echo "     ID: " . $opp['id'] . "\n";
                echo "     Nome: " . ($opp['name'] ?? 'N/A') . "\n";
                echo "     Stage: " . ($opp['stage'] ?? 'N/A') . "\n";
                echo "     Amount: " . ($opp['amount'] ?? 'N/A') . "\n";
                echo "     Probability: " . ($opp['probability'] ?? 'N/A') . "\n";
                echo "     Lead Source: " . ($opp['leadSource'] ?? 'N/A') . "\n";
                echo "     Created: " . ($opp['createdAt'] ?? 'N/A') . "\n";
                echo "     Created By: " . ($opp['createdByName'] ?? 'N/A') . "\n";
            }
        } else {
            echo "\n⚠️ NENHUMA OPORTUNIDADE ENCONTRADA\n";
        }

        return [
            'lead' => $lead,
            'opportunities' => $opportunities,
            'success' => true
        ];
    } else {
        echo "❌ LEAD NÃO ENCONTRADO\n";
        return [
            'lead' => null,
            'opportunities' => [],
            'success' => false
        ];
    }
}

// Função para gerar relatório elegante
function gerarRelatorioElegante($dados, $resultado_webhook, $logs_webhook, $registros_espocrm)
{
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "📊 RELATÓRIO COMPLETO E ELEGANTE DO TESTE\n";
    echo str_repeat("=", 80) . "\n";

    echo "\n🎯 DADOS ENVIADOS:\n";
    echo "   Nome: " . $dados['nome'] . "\n";
    echo "   Email: " . $dados['email'] . "\n";
    echo "   Telefone: " . $dados['telefone'] . "\n";
    echo "   CEP: " . $dados['cep'] . "\n";
    echo "   CPF: " . $dados['cpf'] . "\n";
    echo "   Placa: " . $dados['placa'] . "\n";
    echo "   Ano: " . $dados['ano'] . "\n";
    echo "   Marca: " . $dados['marca'] . "\n";
    echo "   GCLID: " . $dados['gclid'] . "\n";

    echo "\n📡 RESPOSTA DO WEBHOOK:\n";
    echo "   HTTP Code: " . $resultado_webhook['http_code'] . "\n";
    echo "   Status: " . ($resultado_webhook['http_code'] === 200 ? "✅ SUCESSO" : "❌ FALHA") . "\n";
    if ($resultado_webhook['error']) {
        echo "   Erro: " . $resultado_webhook['error'] . "\n";
    }

    echo "\n📋 LOGS DO SERVIDOR:\n";
    echo "   Logs consultados: " . (count($logs_webhook['output']) > 0 ? "✅ ENCONTRADOS" : "❌ NÃO ENCONTRADOS") . "\n";
    if (count($logs_webhook['output']) > 0) {
        echo "   Quantidade de linhas: " . count($logs_webhook['output']) . "\n";
    }

    echo "\n🔍 REGISTROS NO ESPOCRM:\n";
    echo "   Lead encontrado: " . ($registros_espocrm['success'] ? "✅ SIM" : "❌ NÃO") . "\n";
    if ($registros_espocrm['success']) {
        echo "   Lead ID: " . $registros_espocrm['lead']['id'] . "\n";
        echo "   Oportunidades encontradas: " . count($registros_espocrm['opportunities']) . "\n";
    }

    echo "\n📈 STATUS GERAL:\n";
    $status_geral = "❌ FALHA";
    if ($resultado_webhook['http_code'] === 200 && $registros_espocrm['success']) {
        $status_geral = "✅ SUCESSO COMPLETO";
    } elseif ($resultado_webhook['http_code'] === 200) {
        $status_geral = "⚠️ PARCIAL - Webhook OK, mas EspoCRM falhou";
    }

    echo "   $status_geral\n";

    echo "\n" . str_repeat("=", 80) . "\n";
}

// EXECUTAR TESTE COMPLETO E ELEGANTE
echo "🚀 Iniciando teste completo e elegante...\n";

// 1) Gerar dados únicos
$dados = gerarDadosUnicos();

// 2) Testar webhook
$resultado_webhook = testarWebhookCompleto($dados);

// 3) Consultar logs reais do servidor
$logs_webhook = consultarLogsReais($dados['email'], $dados['nome'], $dados['timestamp']);

// 4) Aguardar processamento
echo "\n⏳ Aguardando 5 segundos para processamento completo...\n";
sleep(5);

// 5) Consultar registros no EspoCRM
$registros_espocrm = consultarRegistrosEspoCrmCompleto($dados['email'], $dados['nome']);

// 6) Gerar relatório elegante
gerarRelatorioElegante($dados, $resultado_webhook, $logs_webhook, $registros_espocrm);

echo "\n🎉 TESTE COMPLETO E ELEGANTE FINALIZADO!\n";
echo "   Todos os dados foram processados localmente no Windows\n";
echo "   Logs reais foram consultados no servidor\n";
echo "   Registros foram verificados no EspoCRM\n";
echo "   Relatório completo foi gerado\n";
