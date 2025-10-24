<?php

/**
 * WEBHOOK TRAVELANGELS - AMBIENTE DE DESENVOLVIMENTO COMPLETO
 * mdmidia/dev/webhooks/add_travelangels_dev.php
 * 
 * Versão de desenvolvimento que replica 100% da funcionalidade do arquivo de produção
 * Baseado no add_travelangels.php (produção) com adaptações para ambiente de desenvolvimento
 */

// Incluir configuração de desenvolvimento
require_once __DIR__ . '/../config/dev_config.php';

// Validar ambiente de desenvolvimento
validateDevEnvironment();

// Configurações específicas do webhook de desenvolvimento
$WEBFLOW_SECRET_TRAVELANGELS = $DEV_WEBFLOW_SECRETS['travelangels'];
$DEBUG_LOG_FILE = $DEV_LOGGING['travelangels'];
$LOG_PREFIX = '[DEV-TRAVELANGELS] ';

// Headers de resposta para desenvolvimento
header('Content-Type: application/json; charset=utf-8');
header('X-Environment: development');
header('X-API-Version: 2.0-dev');
header('X-Webhook: travelangels-dev');

// Função para log específico de desenvolvimento
function logDevWebhook($event, $data, $success = true)
{
    global $DEBUG_LOG_FILE, $LOG_PREFIX, $is_dev;

    if (!$is_dev) return;

    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'environment' => 'development',
        'webhook' => 'travelangels',
        'event' => $event,
        'success' => $success,
        'data' => $data,
        'request_id' => uniqid('dev_travel_', true),
        'memory_usage' => memory_get_usage(true),
        'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
    ];

    $log_entry = $LOG_PREFIX . json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
    file_put_contents($DEBUG_LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);
}

// Função para corrigir JSON malformado do Webflow (IDÊNTICA À PRODUÇÃO)
function fixWebflowJson($json_string)
{
    // Tentar decodificar primeiro
    $data = json_decode($json_string, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    }
    
    // Se falhou, tentar corrigir aspas duplas mal escapadas
    $fixed = $json_string;
    
    // Corrigir padrão específico: "texto"texto" -> "texto\"texto"
    $fixed = preg_replace('/"([^"]*)"([^"]*)"([^"]*)"/', '"$1\\"$2\\"$3"', $fixed);
    
    $data = json_decode($fixed, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    }
    
    // Se ainda falhou, tentar abordagem mais agressiva
    $fixed = str_replace('""', '\\"', $fixed);
    $data = json_decode($fixed, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    }
    
    // Última tentativa: extrair dados usando regex e reconstruir
    $extracted_data = [];
    
    // Extrair campos usando regex
    $patterns = [
        'NOME' => '/"NOME":"([^"]+)"/',
        'Email' => '/"Email":"([^"]+)"/',
        'CELULAR' => '/"CELULAR":"([^"]+)"/',
        'CPF' => '/"CPF":"([^"]+)"/',
        'PLACA' => '/"PLACA":"([^"]+)"/',
        'CEP' => '/"CEP":"([^"]+)"/',
        'ANO' => '/"ANO":"([^"]+)"/',
        'MARCA' => '/"MARCA":"([^"]+)"/',
        'DDD-CELULAR' => '/"DDD-CELULAR":"([^"]+)"/'
    ];
    
    foreach ($patterns as $field => $pattern) {
        if (preg_match($pattern, $json_string, $matches)) {
            $extracted_data[$field] = $matches[1];
        }
    }
    
    if (!empty($extracted_data)) {
        // Reconstruir estrutura válida
        return [
            'triggerType' => 'form_submission',
            'payload' => [
                'name' => 'Home',
                'siteId' => '68f77ea29d6b098f6bcad795',
                'data' => $extracted_data,
                'submittedAt' => date('c'),
                'id' => uniqid('webflow_'),
                'formId' => '68f788bd5dc3f2ca4483eee0',
                'formElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f783',
                'pageId' => '68f77ea29d6b098f6bcad76f',
                'publishedPath' => '/',
                'pageUrl' => 'https://segurosimediato-8119bf26e77bf4ff336a58e.webflow.io/',
                'schema' => []
            ]
        ];
    }
    
    return null;
}

// Função para buscar lead por email (IDÊNTICA À PRODUÇÃO)
function findLeadByEmail($email, $client, $logs)
{
    try {
        $leads = $client->request('GET', 'Lead', [
            'where' => [
                'emailAddress' => $email
            ],
            'maxSize' => 1
        ]);

        if (isset($leads['list']) && count($leads['list']) > 0) {
            logDevWebhook("Lead encontrado por email: " . $leads['list'][0]['id'], [], true);
            return $leads['list'][0];
        }
        logDevWebhook("Nenhum lead encontrado para o email: " . $email, [], true);
        return null;
    } catch (Exception $e) {
        logDevWebhook("Erro ao buscar lead por email: " . $e->getMessage(), [], false);
        return null;
    }
}

// Função para enviar resposta de desenvolvimento
function sendDevWebhookResponse($success, $message, $data = null)
{
    $response = [
        'status' => $success ? 'success' : 'error',
        'message' => $message,
        'environment' => 'development',
        'timestamp' => date('Y-m-d H:i:s'),
        'webhook' => 'travelangels-dev'
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    http_response_code($success ? 200 : 400);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// Log de início da requisição
logDevWebhook('webhook_started', [
    'method' => $_SERVER['REQUEST_METHOD'],
    'headers' => getallheaders(),
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'unknown'
], true);

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logDevWebhook('invalid_method', ['method' => $_SERVER['REQUEST_METHOD']], false);
    sendDevWebhookResponse(false, 'Método não permitido');
    exit;
}

// Obter dados da requisição
$raw_input = file_get_contents('php://input');
logDevWebhook('raw_input_received', ['length' => strlen($raw_input), 'preview' => substr($raw_input, 0, 200) . '...'], true);

// Tentar decodificar JSON com correção automática (IDÊNTICO À PRODUÇÃO)
$data = json_decode($raw_input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    logDevWebhook('json_decode_error', [
        'error' => json_last_error_msg(),
        'raw_input_preview' => substr($raw_input, 0, 200) . '...'
    ], false);
    
    logDevWebhook('attempting_json_fix', ['original_error' => json_last_error_msg()], false);
    
    $data = fixWebflowJson($raw_input);
    
    if ($data === null) {
        logDevWebhook('json_fix_failed', ['status' => 'failed'], false);
        sendDevWebhookResponse(false, 'JSON malformado - não foi possível corrigir');
        exit;
    }
    
    logDevWebhook('json_fixed', ['status' => 'success'], true);
}

logDevWebhook('data_decoded', $data, true);

// Incluir classe do CRM (mesma da produção)
require_once __DIR__ . '/../../class.php';

// Cliente para FlyingDonkeys (desenvolvimento)
$clientFlyingDonkeys = new EspoApiClient($DEV_CRM_CONFIG['flyingdonkeys_api_url']);
$clientFlyingDonkeys->setApiKey($DEV_ESPOCRM_CREDENTIALS['api_key']);

logDevWebhook('crm_client_initialized', [
    'url' => $DEV_CRM_CONFIG['flyingdonkeys_api_url'],
    'api_key_length' => strlen($DEV_ESPOCRM_CREDENTIALS['api_key'])
], true);

// Mapeamento adaptativo dos campos recebidos (IDÊNTICO À PRODUÇÃO)
// Estrutura 1: campos diretos (formulário simples)
// Estrutura 2: campos aninhados (Webflow API V2)
$name = isset($data['nome']) ? $data['nome'] : (isset($data['data']['NOME']) ? $data['data']['NOME'] : '');
$telefone = isset($data['telefone']) ? $data['telefone'] : (isset($data['data']['DDD-CELULAR']) && isset($data['data']['CELULAR']) ? $data['data']['DDD-CELULAR'] . $data['data']['CELULAR'] : '');
$email = isset($data['email']) ? $data['email'] : (isset($data['data']['Email']) ? $data['data']['Email'] : '');
$cep = isset($data['cep']) ? $data['cep'] : (isset($data['data']['CEP']) ? $data['data']['CEP'] : '');
$cpf = isset($data['cpf']) ? $data['cpf'] : (isset($data['data']['CPF']) ? $data['data']['CPF'] : '');
$marca = isset($data['marca']) ? $data['marca'] : (isset($data['data']['MARCA']) ? $data['data']['MARCA'] : '');
$placa = isset($data['placa']) ? $data['placa'] : (isset($data['data']['PLACA']) ? $data['data']['PLACA'] : '');
$ano = isset($data['ano']) ? $data['ano'] : (isset($data['data']['ANO']) ? $data['data']['ANO'] : '');
$gclid = isset($data['gclid']) ? $data['gclid'] : (isset($data['data']['GCLID_FLD']) ? $data['data']['GCLID_FLD'] : '');
$endereco = isset($data['endereco']) ? $data['endereco'] : '';
$cidade = isset($data['cidade']) ? $data['cidade'] : '';
$estado = isset($data['estado']) ? $data['estado'] : '';
$veiculo = isset($data['veiculo']) ? $data['veiculo'] : '';
$webpage = 'bpsegurosimediato.com.br'; // Ambiente de desenvolvimento
$source = 'Site';

logDevWebhook('field_mapping', [
    'name' => $name,
    'telefone' => $telefone,
    'email' => $email,
    'cep' => $cep,
    'cpf' => $cpf,
    'marca' => $marca,
    'placa' => $placa,
    'ano' => $ano,
    'gclid' => $gclid,
    'webpage' => $webpage,
    'source' => $source
], true);

// Payload comum para FlyingDonkeys (IDÊNTICO À PRODUÇÃO)
$payload = [
    'firstName' => $name,
    'emailAddress' => $email,
    'cCelular' => $telefone,
    'addressPostalCode' => $cep,
    'addressCity' => $cidade,
    'addressState' => $estado,
    'addressCountry' => 'Brasil',
    'addressStreet' => $endereco,
    'cCpftext' => $cpf,
    'cMarca' => $marca,
    'cPlaca' => $placa,
    'cAnoMod' => $ano,
    'cGclid' => $gclid,
    'cWebpage' => $webpage,
    'source' => $source,
];

logDevWebhook('payload_prepared', $payload, true);

$leadIdFlyingDonkeys = null;

// ===== PROCESSAMENTO FLYINGDONKEYS (LÓGICA COMPLETA DA PRODUÇÃO) =====
logDevWebhook('processing_flyingdonkeys', ['status' => 'started'], true);

// Tentar criar lead no FlyingDonkeys
try {
    $responseFlyingDonkeys = $clientFlyingDonkeys->request('POST', 'Lead', $payload);
    $leadIdFlyingDonkeys = $responseFlyingDonkeys['id'];
    logDevWebhook('flyingdonkeys_lead_created', ['lead_id' => $leadIdFlyingDonkeys], true);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    logDevWebhook('flyingdonkeys_exception', ['error' => $errorMessage], false);

    // Se erro 409 (duplicata) ou se a resposta contém dados do lead (EspoCRM retorna lead existente como "erro")
    if (
        strpos($errorMessage, '409') !== false || strpos($errorMessage, 'duplicate') !== false ||
        (strpos($errorMessage, '"id":') !== false && strpos($errorMessage, '"name":') !== false)
    ) {

        logDevWebhook('duplicate_lead_detected', ['email' => $email], true);

        $existingLead = findLeadByEmail($email, $clientFlyingDonkeys, null);
        if ($existingLead) {
            logDevWebhook('existing_lead_found', ['lead_id' => $existingLead['id']], true);

            // Atualizar lead existente
            $updateResponse = $clientFlyingDonkeys->request('PATCH', 'Lead/' . $existingLead['id'], $payload);
            logDevWebhook('lead_updated', ['lead_id' => $existingLead['id']], true);
            $leadIdFlyingDonkeys = $existingLead['id'];
        } else {
            // Se não encontrou por email, mas a resposta contém dados do lead, usar esses dados
            if (strpos($errorMessage, '"id":') !== false && strpos($errorMessage, '"name":') !== false) {
                $leadData = json_decode($errorMessage, true);
                if (isset($leadData[0]['id'])) {
                    logDevWebhook('using_lead_from_response', ['lead_id' => $leadData[0]['id']], true);
                    $leadIdFlyingDonkeys = $leadData[0]['id'];
                } else {
                    logDevWebhook('duplicate_lead_not_found', ['error' => 'Lead duplicado mas não encontrado por email'], false);
                    throw $e;
                }
            } else {
                logDevWebhook('duplicate_lead_not_found', ['error' => 'Lead duplicado mas não encontrado por email'], false);
                throw $e;
            }
        }
    } else {
        logDevWebhook('real_error_creating_lead', ['error' => $errorMessage], false);
        throw $e;
    }
}

// Tentar criar oportunidade no FlyingDonkeys (IDÊNTICO À PRODUÇÃO)
if ($leadIdFlyingDonkeys) {
    try {
        $opportunityPayload = [
            'name' => $name,
            'leadId' => $leadIdFlyingDonkeys,
            'stage' => 'Novo Sem Contato',
            'amount' => 0,
            'probability' => 10,

            // Campos do lead mapeados para oportunidade (IDÊNTICO À PRODUÇÃO)
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
            'leadSource' => $source,

            // Campos adicionais do workflow (IDÊNTICO À PRODUÇÃO)
            'cSegpref' => isset($data['seguradora_preferencia']) ? $data['seguradora_preferencia'] : '',
            'cValorpret' => isset($data['valor_preferencia']) ? $data['valor_preferencia'] : '',
            'cModalidade' => isset($data['modalidade_seguro']) ? $data['modalidade_seguro'] : '',
            'cSegant' => isset($data['seguradora_apolice']) ? $data['seguradora_apolice'] : '',
            'cCiapol' => isset($data['ci']) ? $data['ci'] : '',
        ];

        logDevWebhook('opportunity_payload_prepared', $opportunityPayload, true);

        $responseOpportunity = $clientFlyingDonkeys->request('POST', 'Opportunity', $opportunityPayload);
        logDevWebhook('opportunity_created', ['opportunity_id' => $responseOpportunity['id']], true);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        logDevWebhook('opportunity_exception', ['error' => $errorMessage], false);

        // Se erro 409 (duplicata), criar nova oportunidade com duplicate = yes
        if (strpos($errorMessage, '409') !== false || strpos($errorMessage, 'duplicate') !== false) {
            logDevWebhook('duplicate_opportunity_detected', ['creating_with_duplicate_yes' => true], true);

            $opportunityPayload['duplicate'] = 'yes';
            $responseOpportunity = $clientFlyingDonkeys->request('POST', 'Opportunity', $opportunityPayload);
            logDevWebhook('duplicate_opportunity_created', ['opportunity_id' => $responseOpportunity['id']], true);
        } else {
            logDevWebhook('real_error_creating_opportunity', ['error' => $errorMessage], false);
        }
    }
}

logDevWebhook('processing_completed', [
    'lead_id' => $leadIdFlyingDonkeys,
    'environment' => 'development'
], true);

// Retorna resposta de sucesso para o webhook
sendDevWebhookResponse(true, 'Lead processado no FlyingDonkeys com sucesso (ambiente de desenvolvimento)', [
    'leadIdFlyingDonkeys' => $leadIdFlyingDonkeys,
    'environment' => 'development',
    'api_version' => '2.0-dev',
    'webhook' => 'travelangels-dev'
]);

logDevWebhook('webhook_completed', [
    'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
    'memory_peak' => memory_get_peak_usage(true)
], true);
?>
