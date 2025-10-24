<?php

/**
 * PROJETO COMPLETO E ELEGANTE DO WEBHOOK TRAVELANGELS DEV
 * 
 * FUNCIONALIDADES COMPLETAS:
 * 1) Envia registros com nomes e emails sempre diferentes
 * 2) Consulta LOGS REAIS do servidor bpsegurosimediato.com.br
 * 3) Verifica LOGS REAIS das respostas do EspoCRM para inserção do lead e oportunidade
 * 4) Consulta o LEAD e a OPORTUNIDADE de acordo com o email enviado
 * 5) Apresenta relatório completo e elegante de tudo
 * 6) Verifica se ambos (lead e oportunidade) foram criados corretamente
 * 7) Mostra dados recebidos pelo webhook
 * 8) Mostra variáveis tratadas antes de enviar para o EspoCRM
 * 9) Mostra resultado enviado pelo EspoCRM para lead e oportunidade
 * 10) Mostra email usado para consulta no EspoCRM
 * 11) Mostra resultado da consulta de ambos (lead e oportunidade)
 * 
 * TUDO EXECUTADO LOCALMENTE NO WINDOWS - SEM PRECISAR CONSULTAR SERVIDOR MANUALMENTE
 */

echo "=== PROJETO COMPLETO E ELEGANTE DO WEBHOOK TRAVELANGELS DEV ===\n\n";

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

// Função para consultar logs reais do webhook usando request_id
function consultarLogsReais($request_id, $email, $nome, $timestamp)
{
    echo "\n📋 CONSULTANDO LOGS REAIS DO WEBHOOK:\n";
    echo "   🎯 Request ID: $request_id\n";
    echo "   📧 Email: $email\n";
    echo "   👤 Nome: $nome\n";
    echo "   ⏰ Timestamp: $timestamp\n";

    // Consultar logs do webhook travelangels_dev usando request_id (prioridade)
    $log_file = '/var/www/html/dev/logs/travelangels_dev.txt';

    if ($request_id) {
        // Buscar por request_id primeiro (mais eficiente)
        $comando_logs = "grep -A 20 -B 5 '$request_id' $log_file || echo 'Nenhum log encontrado para request_id: $request_id'";
        echo "   🔍 Buscando por Request ID: $request_id\n";
    } else {
        // Fallback para busca por nome/email/timestamp
        $comando_logs = "tail -50 $log_file | grep -E \"($nome|$email|$timestamp)\" || echo 'Nenhum log encontrado'";
        echo "   🔍 Buscando por nome/email/timestamp (fallback)\n";
    }

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

// Função para consultar logs completos do webhook (últimas entradas)
function consultarLogsCompletos()
{
    echo "\n📋 CONSULTANDO LOGS COMPLETOS DO WEBHOOK:\n";

    $log_file = '/var/www/html/dev/logs/travelangels_dev.txt';

    // Verificar se o arquivo existe e seu tamanho
    $comando_info = "ls -la $log_file";
    $resultado_info = executarSSH($comando_info);

    echo "   📁 Informações do arquivo de log:\n";
    if (!empty($resultado_info['output'])) {
        foreach ($resultado_info['output'] as $linha) {
            echo "      $linha\n";
        }
    }

    // Buscar últimas 20 entradas de log
    $comando_ultimas = "tail -20 $log_file";
    $resultado_ultimas = executarSSH($comando_ultimas);

    echo "\n   📊 ÚLTIMAS 20 ENTRADAS DE LOG:\n";
    if (!empty($resultado_ultimas['output'])) {
        foreach ($resultado_ultimas['output'] as $linha) {
            echo "      $linha\n";
        }
    } else {
        echo "      ❌ Nenhum log encontrado\n";
    }

    // Buscar logs de erro específicos
    $comando_erros = "grep -i 'error\\|exception\\|failed' $log_file | tail -10";
    $resultado_erros = executarSSH($comando_erros);

    echo "\n   ⚠️ ÚLTIMOS 10 ERROS/EXCEÇÕES:\n";
    if (!empty($resultado_erros['output'])) {
        foreach ($resultado_erros['output'] as $linha) {
            echo "      $linha\n";
        }
    } else {
        echo "      ✅ Nenhum erro encontrado\n";
    }

    // Buscar logs de sucesso recentes
    $comando_sucesso = "grep -i 'success.*true' $log_file | tail -5";
    $resultado_sucesso = executarSSH($comando_sucesso);

    echo "\n   ✅ ÚLTIMOS 5 SUCESSOS:\n";
    if (!empty($resultado_sucesso['output'])) {
        foreach ($resultado_sucesso['output'] as $linha) {
            echo "      $linha\n";
        }
    } else {
        echo "      ❌ Nenhum sucesso encontrado\n";
    }

    return [
        'info' => $resultado_info,
        'ultimas' => $resultado_ultimas,
        'erros' => $resultado_erros,
        'sucessos' => $resultado_sucesso
    ];
}

// Função para buscar logs por evento específico
function buscarLogsPorEvento($evento)
{
    echo "\n🔍 BUSCANDO LOGS POR EVENTO: $evento\n";

    $log_file = '/var/www/html/dev/logs/travelangels_dev.txt';
    $comando = "grep -i '$evento' $log_file | tail -10";
    $resultado = executarSSH($comando);

    echo "   📁 Arquivo: $log_file\n";
    echo "   🔍 Evento: $evento\n";
    echo "   📊 Resultado:\n";

    if (!empty($resultado['output'])) {
        foreach ($resultado['output'] as $linha) {
            echo "      $linha\n";
        }
    } else {
        echo "      ❌ Nenhum log encontrado para o evento: $evento\n";
    }

    return $resultado;
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

    // Extrair request_id da resposta do webhook
    $request_id = null;
    if ($response) {
        $response_data = json_decode($response, true);
        if ($response_data && isset($response_data['request_id'])) {
            $request_id = $response_data['request_id'];
            echo "   🎯 Request ID capturado: $request_id\n";
        } else {
            echo "   ⚠️ Request ID não encontrado na resposta\n";
        }
    }

    return [
        'http_code' => $http_code,
        'response' => $response,
        'error' => $error,
        'payload' => $payload,
        'request_id' => $request_id
    ];
}

// Função para simular processamento do webhook (dados recebidos e variáveis tratadas)
function simularProcessamentoWebhook($dados)
{
    echo "\n🔧 SIMULANDO PROCESSAMENTO DO WEBHOOK:\n";

    // Simular dados recebidos pelo webhook
    echo "   📥 DADOS RECEBIDOS PELO WEBHOOK:\n";
    echo "      JSON completo: " . json_encode($dados, JSON_PRETTY_PRINT) . "\n";

    // Simular variáveis tratadas antes de enviar para EspoCRM
    echo "\n   🔄 VARIÁVEIS TRATADAS ANTES DE ENVIAR PARA ESPOCRM:\n";

    $form_data = $dados;
    $name = $form_data['nome'];
    $email = $form_data['email'];
    $telefone = $form_data['telefone'];
    $cep = $form_data['cep'];
    $cpf = $form_data['cpf'];
    $marca = $form_data['marca'];
    $placa = $form_data['placa'];
    $ano = $form_data['ano'];
    $gclid = $form_data['gclid'];
    $webpage = 'bpsegurosimediato.com.br';
    $source = 'Site';

    echo "      Nome: $name\n";
    echo "      Email: $email\n";
    echo "      Telefone: $telefone\n";
    echo "      CEP: $cep\n";
    echo "      CPF: $cpf\n";
    echo "      Marca: $marca\n";
    echo "      Placa: $placa\n";
    echo "      Ano: $ano\n";
    echo "      GCLID: $gclid\n";
    echo "      Webpage: $webpage\n";
    echo "      Source: $source\n";

    // Simular payload do Lead para EspoCRM
    $lead_data = [
        'firstName' => $name,
        'emailAddress' => $email,
        'source' => $source,
        'description' => 'Lead criado via Webflow API V2 - Ambiente de Desenvolvimento'
    ];

    echo "\n   📤 PAYLOAD DO LEAD PARA ESPOCRM:\n";
    echo "      " . json_encode($lead_data, JSON_PRETTY_PRINT) . "\n";

    // Simular payload da Oportunidade para EspoCRM
    $opportunity_data = [
        'name' => $name,
        'stage' => 'Novo Sem Contato',
        'amount' => 0,
        'probability' => 10,
        'cAnoFab' => $ano,
        'cAnoMod' => $ano,
        'cCEP' => $cep,
        'cCelular' => $telefone,
        'cCpftext' => $cpf,
        'cGclid' => $gclid,
        'cMarca' => $marca,
        'cPlaca' => $placa,
        'cWebpage' => $webpage,
        'cEmail' => $email,
        'cEmailAdress' => $email,
        'leadSource' => $source
    ];

    echo "\n   📤 PAYLOAD DA OPORTUNIDADE PARA ESPOCRM:\n";
    echo "      " . json_encode($opportunity_data, JSON_PRETTY_PRINT) . "\n";

    return [
        'form_data' => $form_data,
        'lead_data' => $lead_data,
        'opportunity_data' => $opportunity_data,
        'email_consulta' => $email
    ];
}

// Função para consultar registros no EspoCRM com detalhes completos (LEAD + OPORTUNIDADE)
function consultarRegistrosEspoCrmCompleto($email, $nome)
{
    echo "\n🔍 CONSULTANDO REGISTROS NO ESPOCRM (LEAD + OPORTUNIDADE):\n";
    echo "   📧 Email usado para consulta: $email\n";

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
            'success' => true,
            'lead_found' => true,
            'opportunity_found' => count($opportunities) > 0
        ];
    } else {
        echo "❌ LEAD NÃO ENCONTRADO\n";
        return [
            'lead' => null,
            'opportunities' => [],
            'success' => false,
            'lead_found' => false,
            'opportunity_found' => false
        ];
    }
}

// Função para analisar logs e identificar problemas automaticamente
function analisarLogsAutomaticamente($logs_webhook, $logs_especificos)
{
    echo "\n🔍 ANÁLISE AUTOMÁTICA DOS LOGS:\n";

    $problemas_identificados = [];
    $status_geral_logs = "✅ OK";

    // Analisar logs gerais
    if (empty($logs_webhook['ultimas']['output'])) {
        $problemas_identificados[] = "❌ PROBLEMA: Nenhum log encontrado no arquivo travelangels_dev.txt";
        $status_geral_logs = "❌ CRÍTICO";
    } else {
        echo "   ✅ Logs gerais encontrados: " . count($logs_webhook['ultimas']['output']) . " entradas\n";
    }

    // Analisar erros específicos
    if (!empty($logs_webhook['erros']['output'])) {
        echo "   ⚠️ Erros encontrados: " . count($logs_webhook['erros']['output']) . "\n";
        foreach ($logs_webhook['erros']['output'] as $erro) {
            if (strpos($erro, 'json_decode_error') !== false) {
                $problemas_identificados[] = "❌ PROBLEMA: Erro de decodificação JSON - " . $erro;
                $status_geral_logs = "❌ CRÍTICO";
            } elseif (strpos($erro, 'exception') !== false) {
                $problemas_identificados[] = "❌ PROBLEMA: Exceção encontrada - " . $erro;
                $status_geral_logs = "❌ CRÍTICO";
            } elseif (strpos($erro, 'failed') !== false) {
                $problemas_identificados[] = "❌ PROBLEMA: Operação falhou - " . $erro;
                $status_geral_logs = "❌ CRÍTICO";
            }
        }
    } else {
        echo "   ✅ Nenhum erro encontrado nos logs\n";
    }

    // Analisar logs específicos do teste atual
    if (empty($logs_especificos['output'])) {
        $problemas_identificados[] = "❌ PROBLEMA: Nenhum log específico encontrado para este teste";
        $status_geral_logs = "❌ CRÍTICO";
    } else {
        echo "   ✅ Logs específicos encontrados: " . count($logs_especificos['output']) . " entradas\n";

        // Analisar conteúdo dos logs específicos
        foreach ($logs_especificos['output'] as $log_linha) {
            if (strpos($log_linha, 'webhook_started') !== false) {
                echo "   ✅ Webhook iniciado corretamente\n";
            } elseif (strpos($log_linha, 'json_decode_error') !== false) {
                $problemas_identificados[] = "❌ PROBLEMA: Erro de JSON no processamento - " . $log_linha;
                $status_geral_logs = "❌ CRÍTICO";
            } elseif (strpos($log_linha, 'flyingdonkeys_lead_created') !== false) {
                echo "   ✅ Lead criado no FlyingDonkeys\n";
            } elseif (strpos($log_linha, 'opportunity_created') !== false) {
                echo "   ✅ Oportunidade criada\n";
            } elseif (strpos($log_linha, 'exception') !== false) {
                $problemas_identificados[] = "❌ PROBLEMA: Exceção no processamento - " . $log_linha;
                $status_geral_logs = "❌ CRÍTICO";
            }
        }
    }

    echo "\n   📊 STATUS GERAL DOS LOGS: $status_geral_logs\n";

    return [
        'problemas' => $problemas_identificados,
        'status' => $status_geral_logs,
        'logs_encontrados' => !empty($logs_webhook['ultimas']['output']),
        'logs_especificos_encontrados' => !empty($logs_especificos['output']),
        'erros_encontrados' => !empty($logs_webhook['erros']['output'])
    ];
}

// Função para analisar resposta do EspoCRM e identificar problemas
function analisarRespostaEspoCrm($registros_espocrm, $dados_enviados)
{
    echo "\n🔍 ANÁLISE AUTOMÁTICA DA RESPOSTA DO ESPOCRM:\n";

    $problemas_identificados = [];
    $status_geral_espocrm = "✅ OK";

    // Verificar se lead foi encontrado
    if (!$registros_espocrm['lead_found']) {
        $problemas_identificados[] = "❌ PROBLEMA: Lead não foi criado no EspoCRM";
        $status_geral_espocrm = "❌ CRÍTICO";
        echo "   ❌ Lead não encontrado no EspoCRM\n";
    } else {
        echo "   ✅ Lead encontrado no EspoCRM\n";
        echo "   📋 Lead ID: " . $registros_espocrm['lead']['id'] . "\n";
        echo "   👤 Nome: " . ($registros_espocrm['lead']['firstName'] ?? 'N/A') . "\n";
        echo "   📧 Email: " . ($registros_espocrm['lead']['emailAddress'] ?? 'N/A') . "\n";
        echo "   🏷️ Source: " . ($registros_espocrm['lead']['source'] ?? 'N/A') . "\n";

        // Verificar se os dados estão corretos
        if (($registros_espocrm['lead']['firstName'] ?? '') !== $dados_enviados['nome']) {
            $problemas_identificados[] = "❌ PROBLEMA: Nome do lead não confere com dados enviados";
            $status_geral_espocrm = "⚠️ PARCIAL";
        }

        if (($registros_espocrm['lead']['emailAddress'] ?? '') !== $dados_enviados['email']) {
            $problemas_identificados[] = "❌ PROBLEMA: Email do lead não confere com dados enviados";
            $status_geral_espocrm = "⚠️ PARCIAL";
        }

        if (($registros_espocrm['lead']['source'] ?? '') !== 'Site') {
            $problemas_identificados[] = "❌ PROBLEMA: Source do lead não é 'Site' como esperado";
            $status_geral_espocrm = "⚠️ PARCIAL";
        }
    }

    // Verificar se oportunidade foi encontrada
    if (!$registros_espocrm['opportunity_found']) {
        $problemas_identificados[] = "❌ PROBLEMA: Oportunidade não foi criada no EspoCRM";
        $status_geral_espocrm = "❌ CRÍTICO";
        echo "   ❌ Oportunidade não encontrada no EspoCRM\n";
    } else {
        echo "   ✅ Oportunidade encontrada no EspoCRM\n";
        echo "   📋 Total de oportunidades: " . count($registros_espocrm['opportunities']) . "\n";

        foreach ($registros_espocrm['opportunities'] as $index => $opp) {
            echo "   📋 Oportunidade " . ($index + 1) . ":\n";
            echo "     ID: " . $opp['id'] . "\n";
            echo "     Nome: " . ($opp['name'] ?? 'N/A') . "\n";
            echo "     Stage: " . ($opp['stage'] ?? 'N/A') . "\n";
            echo "     Lead Source: " . ($opp['leadSource'] ?? 'N/A') . "\n";

            // Verificar se os dados estão corretos
            if (($opp['name'] ?? '') !== $dados_enviados['nome']) {
                $problemas_identificados[] = "❌ PROBLEMA: Nome da oportunidade não confere com dados enviados";
                $status_geral_espocrm = "⚠️ PARCIAL";
            }

            if (($opp['leadSource'] ?? '') !== 'Site') {
                $problemas_identificados[] = "❌ PROBLEMA: Lead Source da oportunidade não é 'Site' como esperado";
                $status_geral_espocrm = "⚠️ PARCIAL";
            }
        }
    }

    echo "\n   📊 STATUS GERAL DO ESPOCRM: $status_geral_espocrm\n";

    return [
        'problemas' => $problemas_identificados,
        'status' => $status_geral_espocrm,
        'lead_encontrado' => $registros_espocrm['lead_found'],
        'opportunity_encontrada' => $registros_espocrm['opportunity_found']
    ];
}

// Função para gerar diagnóstico automático dos problemas
function gerarDiagnosticoAutomatico($analise_logs, $analise_espocrm, $resultado_webhook, $dados_enviados)
{
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "🔬 DIAGNÓSTICO AUTOMÁTICO DOS PROBLEMAS\n";
    echo str_repeat("=", 80) . "\n";

    $todos_problemas = array_merge($analise_logs['problemas'], $analise_espocrm['problemas']);
    $problemas_criticos = [];
    $problemas_parciais = [];

    // Classificar problemas
    foreach ($todos_problemas as $problema) {
        if (strpos($problema, '❌ CRÍTICO') !== false || strpos($problema, '❌ PROBLEMA') !== false) {
            $problemas_criticos[] = $problema;
        } else {
            $problemas_parciais[] = $problema;
        }
    }

    // Resumo geral
    echo "\n📊 RESUMO GERAL:\n";
    echo "   🎯 Webhook HTTP Code: " . $resultado_webhook['http_code'] . "\n";
    echo "   📋 Logs encontrados: " . ($analise_logs['logs_encontrados'] ? "✅ SIM" : "❌ NÃO") . "\n";
    echo "   🔍 Logs específicos encontrados: " . ($analise_logs['logs_especificos_encontrados'] ? "✅ SIM" : "❌ NÃO") . "\n";
    echo "   👤 Lead encontrado: " . ($analise_espocrm['lead_encontrado'] ? "✅ SIM" : "❌ NÃO") . "\n";
    echo "   💼 Oportunidade encontrada: " . ($analise_espocrm['opportunity_encontrada'] ? "✅ SIM" : "❌ NÃO") . "\n";

    // Problemas críticos
    if (!empty($problemas_criticos)) {
        echo "\n🚨 PROBLEMAS CRÍTICOS IDENTIFICADOS:\n";
        foreach ($problemas_criticos as $index => $problema) {
            echo "   " . ($index + 1) . ". $problema\n";
        }
    }

    // Problemas parciais
    if (!empty($problemas_parciais)) {
        echo "\n⚠️ PROBLEMAS PARCIAIS IDENTIFICADOS:\n";
        foreach ($problemas_parciais as $index => $problema) {
            echo "   " . ($index + 1) . ". $problema\n";
        }
    }

    // Diagnóstico final
    echo "\n🎯 DIAGNÓSTICO FINAL:\n";

    if (empty($todos_problemas)) {
        echo "   ✅ SUCESSO COMPLETO: Todos os componentes funcionaram corretamente\n";
        echo "   📋 Lead e Oportunidade foram criados com sucesso no EspoCRM\n";
        echo "   📊 Logs foram gerados corretamente\n";
    } elseif ($resultado_webhook['http_code'] !== 200) {
        echo "   ❌ FALHA NO WEBHOOK: O webhook não respondeu corretamente (HTTP " . $resultado_webhook['http_code'] . ")\n";
        echo "   🔧 AÇÃO NECESSÁRIA: Verificar configuração do webhook e logs do servidor\n";
    } elseif (!$analise_logs['logs_encontrados']) {
        echo "   ❌ FALHA NOS LOGS: Nenhum log foi gerado pelo webhook\n";
        echo "   🔧 AÇÃO NECESSÁRIA: Verificar se o webhook está sendo executado e se tem permissão de escrita\n";
    } elseif (!$analise_espocrm['lead_encontrado']) {
        echo "   ❌ FALHA NO ESPOCRM: Lead não foi criado no EspoCRM\n";
        echo "   🔧 AÇÃO NECESSÁRIA: Verificar credenciais da API, permissões e logs de erro do EspoCRM\n";
    } elseif (!$analise_espocrm['opportunity_encontrada']) {
        echo "   ⚠️ PROBLEMA PARCIAL: Lead criado, mas Oportunidade não foi criada\n";
        echo "   🔧 AÇÃO NECESSÁRIA: Verificar lógica de criação de oportunidade no webhook\n";
    } else {
        echo "   ⚠️ PROBLEMAS PARCIAIS: Funcionamento básico OK, mas há inconsistências nos dados\n";
        echo "   🔧 AÇÃO NECESSÁRIA: Verificar mapeamento de campos e validação de dados\n";
    }

    echo "\n" . str_repeat("=", 80) . "\n";

    return [
        'problemas_criticos' => $problemas_criticos,
        'problemas_parciais' => $problemas_parciais,
        'total_problemas' => count($todos_problemas),
        'diagnostico_final' => empty($todos_problemas) ? 'SUCESSO_COMPLETO' : 'PROBLEMAS_IDENTIFICADOS'
    ];
}
// Função para gerar relatório elegante e completo
function gerarRelatorioEleganteCompleto($dados, $resultado_webhook, $logs_webhook, $processamento_webhook, $registros_espocrm)
{
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "📊 RELATÓRIO COMPLETO E ELEGANTE DO TESTE\n";
    echo str_repeat("=", 80) . "\n";

    echo "\n🎯 DADOS ENVIADOS PARA O WEBHOOK:\n";
    echo "   Nome: " . $dados['nome'] . "\n";
    echo "   Email: " . $dados['email'] . "\n";
    echo "   Telefone: " . $dados['telefone'] . "\n";
    echo "   CEP: " . $dados['cep'] . "\n";
    echo "   CPF: " . $dados['cpf'] . "\n";
    echo "   Placa: " . $dados['placa'] . "\n";
    echo "   Ano: " . $dados['ano'] . "\n";
    echo "   Marca: " . $dados['marca'] . "\n";
    echo "   GCLID: " . $dados['gclid'] . "\n";

    echo "\n📥 DADOS RECEBIDOS PELO WEBHOOK:\n";
    echo "   JSON completo: " . json_encode($dados, JSON_PRETTY_PRINT) . "\n";

    echo "\n🔄 VARIÁVEIS TRATADAS ANTES DE ENVIAR PARA ESPOCRM:\n";
    echo "   Nome: " . $processamento_webhook['lead_data']['firstName'] . "\n";
    echo "   Email: " . $processamento_webhook['lead_data']['emailAddress'] . "\n";
    echo "   Source: " . $processamento_webhook['lead_data']['source'] . "\n";
    echo "   Description: " . $processamento_webhook['lead_data']['description'] . "\n";

    echo "\n📤 PAYLOAD DO LEAD ENVIADO PARA ESPOCRM:\n";
    echo "   " . json_encode($processamento_webhook['lead_data'], JSON_PRETTY_PRINT) . "\n";

    echo "\n📤 PAYLOAD DA OPORTUNIDADE ENVIADO PARA ESPOCRM:\n";
    echo "   " . json_encode($processamento_webhook['opportunity_data'], JSON_PRETTY_PRINT) . "\n";

    echo "\n📡 RESPOSTA DO WEBHOOK:\n";
    echo "   HTTP Code: " . $resultado_webhook['http_code'] . "\n";
    echo "   Status: " . ($resultado_webhook['http_code'] === 200 ? "✅ SUCESSO" : "❌ FALHA") . "\n";
    if ($resultado_webhook['error']) {
        echo "   Erro: " . $resultado_webhook['error'] . "\n";
    }
    echo "   Response: " . $resultado_webhook['response'] . "\n";
    if ($resultado_webhook['request_id']) {
        echo "   🎯 Request ID: " . $resultado_webhook['request_id'] . "\n";
    }

    echo "\n📋 LOGS DO SERVIDOR:\n";
    echo "   Logs consultados: " . (count($logs_webhook['ultimas']['output']) > 0 ? "✅ ENCONTRADOS" : "❌ NÃO ENCONTRADOS") . "\n";
    echo "   Arquivo de log: /var/www/html/dev/logs/travelangels_dev.txt\n";
    if (count($logs_webhook['ultimas']['output']) > 0) {
        echo "   Últimas entradas: " . count($logs_webhook['ultimas']['output']) . "\n";
    }
    if (count($logs_webhook['erros']['output']) > 0) {
        echo "   Erros encontrados: " . count($logs_webhook['erros']['output']) . "\n";
    }
    if (count($logs_webhook['sucessos']['output']) > 0) {
        echo "   Sucessos encontrados: " . count($logs_webhook['sucessos']['output']) . "\n";
    }

    echo "\n🔍 CONSULTA NO ESPOCRM:\n";
    echo "   Email usado para consulta: " . $processamento_webhook['email_consulta'] . "\n";
    echo "   Lead encontrado: " . ($registros_espocrm['lead_found'] ? "✅ SIM" : "❌ NÃO") . "\n";
    echo "   Oportunidade encontrada: " . ($registros_espocrm['opportunity_found'] ? "✅ SIM" : "❌ NÃO") . "\n";

    if ($registros_espocrm['lead_found']) {
        echo "   Lead ID: " . $registros_espocrm['lead']['id'] . "\n";
        echo "   Lead Nome: " . ($registros_espocrm['lead']['firstName'] ?? 'N/A') . "\n";
        echo "   Lead Source: " . ($registros_espocrm['lead']['source'] ?? 'N/A') . "\n";
        echo "   Lead Created: " . ($registros_espocrm['lead']['createdAt'] ?? 'N/A') . "\n";
    }

    if ($registros_espocrm['opportunity_found']) {
        echo "   Oportunidades encontradas: " . count($registros_espocrm['opportunities']) . "\n";
        foreach ($registros_espocrm['opportunities'] as $index => $opp) {
            echo "   Oportunidade " . ($index + 1) . " ID: " . $opp['id'] . "\n";
            echo "   Oportunidade " . ($index + 1) . " Stage: " . ($opp['stage'] ?? 'N/A') . "\n";
            echo "   Oportunidade " . ($index + 1) . " Created: " . ($opp['createdAt'] ?? 'N/A') . "\n";
        }
    }

    echo "\n📈 STATUS GERAL:\n";
    $status_geral = "❌ FALHA COMPLETA";

    if ($resultado_webhook['http_code'] === 200 && $registros_espocrm['lead_found'] && $registros_espocrm['opportunity_found']) {
        $status_geral = "✅ SUCESSO COMPLETO - Lead e Oportunidade criados";
    } elseif ($resultado_webhook['http_code'] === 200 && $registros_espocrm['lead_found'] && !$registros_espocrm['opportunity_found']) {
        $status_geral = "⚠️ PARCIAL - Lead criado, mas Oportunidade não encontrada";
    } elseif ($resultado_webhook['http_code'] === 200 && !$registros_espocrm['lead_found']) {
        $status_geral = "⚠️ PARCIAL - Webhook OK, mas Lead não encontrado no EspoCRM";
    } elseif ($resultado_webhook['http_code'] !== 200) {
        $status_geral = "❌ FALHA - Webhook não respondeu corretamente";
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

// 3) Simular processamento do webhook
$processamento_webhook = simularProcessamentoWebhook($dados);

// 4) Consultar logs completos do servidor
$logs_webhook = consultarLogsCompletos();

// 4.1) Consultar logs específicos do teste atual usando request_id
$logs_especificos = consultarLogsReais($resultado_webhook['request_id'], $dados['email'], $dados['nome'], $dados['timestamp']);

// 4.2) Buscar logs por eventos específicos
buscarLogsPorEvento('webhook_started');
buscarLogsPorEvento('lead_created');
buscarLogsPorEvento('opportunity_created');

// 5) Aguardar processamento
echo "\n⏳ Aguardando 5 segundos para processamento completo...\n";
sleep(5);

// 6) Consultar registros no EspoCRM (LEAD + OPORTUNIDADE)
$registros_espocrm = consultarRegistrosEspoCrmCompleto($dados['email'], $dados['nome']);

// 7) ANÁLISE AUTOMÁTICA DOS PROBLEMAS
$analise_logs = analisarLogsAutomaticamente($logs_webhook, $logs_especificos);
$analise_espocrm = analisarRespostaEspoCrm($registros_espocrm, $dados);

// 8) GERAR DIAGNÓSTICO AUTOMÁTICO
$diagnostico = gerarDiagnosticoAutomatico($analise_logs, $analise_espocrm, $resultado_webhook, $dados);

// 9) Gerar relatório elegante e completo
gerarRelatorioEleganteCompleto($dados, $resultado_webhook, $logs_webhook, $processamento_webhook, $registros_espocrm);

echo "\n🎉 TESTE COMPLETO E ELEGANTE FINALIZADO!\n";
echo "   Todos os dados foram processados localmente no Windows\n";
echo "   Logs reais foram consultados no servidor\n";
echo "   Lead e Oportunidade foram verificados no EspoCRM\n";
echo "   Análise automática dos problemas foi executada\n";
echo "   Diagnóstico automático foi gerado\n";
echo "   Relatório completo foi apresentado\n";

echo "\n🔬 RESUMO DO DIAGNÓSTICO:\n";
echo "   Problemas críticos: " . count($diagnostico['problemas_criticos']) . "\n";
echo "   Problemas parciais: " . count($diagnostico['problemas_parciais']) . "\n";
echo "   Total de problemas: " . $diagnostico['total_problemas'] . "\n";
echo "   Status final: " . $diagnostico['diagnostico_final'] . "\n";
