<?php

/**
 * WEBHOOK TRAVELANGELS - AMBIENTE DE DESENVOLVIMENTO LOCAL
 * mdmidia/dev/webhooks/add_travelangels_dev.php
 * 
 * Versão de desenvolvimento com API V2, logging avançado e validação de signature
 * Baseado no webhook de produção mas com funcionalidades específicas para testes
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

// Função para validar signature do Webflow (API V2)
function validateWebflowSignatureDev($payload, $signature, $timestamp, $secret)
{
    global $is_dev;

    // Em desenvolvimento, permitir requisições sem signature para testes
    if ($is_dev && (empty($signature) || empty($timestamp))) {
        logDevWebhook('signature_validation', ['status' => 'bypassed_dev', 'reason' => 'development_mode'], true);
        return true;
    }

    $expected_signature = hash_hmac('sha256', $timestamp . $payload, $secret);
    $is_valid = hash_equals($expected_signature, $signature);

    if (!$is_valid && $is_dev) {
        logDevWebhook('signature_validation', [
            'status' => 'failed',
            'expected' => $expected_signature,
            'received' => $signature,
            'payload_length' => strlen($payload)
        ], false);
    }

    return $is_valid;
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

// Função para processar dados de teste
function processTestData($data)
{
    // Detectar se são dados de teste
    if (isset($data['test_mode']) || strpos($data['email'] ?? '', '@dev.com') !== false) {
        logDevWebhook('test_data_detected', $data, true);
        return true;
    }
    return false;
}

// Função para simular resposta do CRM
function simulateCrmResponse($data)
{
    return [
        'id' => 'dev_' . uniqid(),
        'name' => $data['name'] ?? 'Teste Dev',
        'email' => $data['email'] ?? 'teste@dev.com',
        'status' => 'simulated',
        'environment' => 'development',
        'timestamp' => date('Y-m-d H:i:s')
    ];
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
$data = json_decode($raw_input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    logDevWebhook('json_decode_error', [
        'error' => json_last_error_msg(),
        'raw_input' => $raw_input
    ], false);
    sendDevWebhookResponse(false, 'Erro ao decodificar JSON');
    exit;
}

logDevWebhook('data_received', $data, true);

// Validar signature do Webflow (API V2)
$signature = $_SERVER['HTTP_X_WEBFLOW_SIGNATURE'] ?? '';
$timestamp = $_SERVER['HTTP_X_WEBFLOW_TIMESTAMP'] ?? '';

if (!validateWebflowSignatureDev($raw_input, $signature, $timestamp, $WEBFLOW_SECRET_TRAVELANGELS)) {
    logDevWebhook('signature_validation_failed', [
        'signature' => $signature,
        'timestamp' => $timestamp
    ], false);
    sendDevWebhookResponse(false, 'Assinatura inválida');
    exit;
}

// Verificar se são dados de teste
if (processTestData($data)) {
    logDevWebhook('test_data_processed', ['original_data' => $data, 'test_mode' => true, 'crm_bypass' => true], true);
    sendDevWebhookResponse(true, 'Dados de teste processados - não enviado para CRM', ['test_mode' => true]);
    exit;
}

// Incluir classe do CRM (mesma da produção)
require_once __DIR__ . '/../../production/webhooks/class.php';

try {
    // Usar cliente CRM real se credenciais estiverem disponíveis
    $crm_available = false;
    $client = null;

    // Verificar se temos credenciais do EspoCRM de desenvolvimento
    if (isset($DEV_ESPOCRM_CREDENTIALS) && !empty($DEV_ESPOCRM_CREDENTIALS['api_key'])) {
        $client = new EspoApiClient($DEV_ESPOCRM_CREDENTIALS['url']);
        $client->setApiKey($DEV_ESPOCRM_CREDENTIALS['api_key']);
        $crm_available = true;

        logDevWebhook('crm_real_connection', [
            'url' => $DEV_ESPOCRM_CREDENTIALS['url'],
            'api_key_length' => strlen($DEV_ESPOCRM_CREDENTIALS['api_key']),
            'mode' => 'real_crm'
        ], true);
    } else {
        // Fallback para simulação se não houver credenciais
        $client = new stdClass();
        $client->request = function ($method, $endpoint, $payload) use ($data) {
            return simulateCrmResponse($data);
        };

        logDevWebhook('crm_simulation_mode', [
            'reason' => 'no_credentials',
            'mode' => 'simulation'
        ], true);
    }

    if (!$crm_available) {
        logDevWebhook('crm_unavailable', ['status' => 'simulated'], false);

        // Em desenvolvimento, simular resposta se CRM falhar
        $simulated_response = simulateCrmResponse($data);
        sendDevWebhookResponse(true, 'CRM indisponível - resposta simulada', $simulated_response);
        exit;
    }

    // Processar dados do lead
    $lead_data = [
        'firstName' => $data['name'] ?? 'Nome não informado',
        'lastName' => '',
        'emailAddress' => $data['email'] ?? '',
        'phoneNumber' => $data['phone'] ?? '',
        'source' => $DEV_TRAVELANGELS_CONFIG['source'], // ✅ CORRETO para Lead
        'description' => 'Lead enviado do ambiente de desenvolvimento'
    ];

    logDevWebhook('lead_data_prepared', $lead_data, true);

    // Criar lead no CRM
    $response = $client->request('POST', 'Lead', $lead_data);

    logDevWebhook('crm_response', [
        'status' => 'success',
        'response' => $response
    ], true);

    // ✅ CORREÇÃO: Criar oportunidade no FlyingDonkeys com leadSource correto
    $opportunityPayload = [
        'name' => $data['name'] ?? 'Nome não informado',
        'leadId' => $response['id'] ?? 'unknown',
        'stage' => 'Novo Sem Contato',
        'amount' => 0,
        'probability' => 10,
        'leadSource' => $DEV_TRAVELANGELS_CONFIG['source'], // ✅ CORRETO para Opportunity (não 'source')
        'description' => 'Oportunidade criada no ambiente de desenvolvimento'
    ];

    logDevWebhook('opportunity_data_prepared', $opportunityPayload, true);

    $opportunityResponse = $client->request('POST', 'Opportunity', $opportunityPayload);

    logDevWebhook('opportunity_response', [
        'status' => 'success',
        'response' => $opportunityResponse
    ], true);

    sendDevWebhookResponse(true, 'Lead e Oportunidade criados com sucesso no ambiente de desenvolvimento', [
        'lead_id' => $response['id'] ?? 'unknown',
        'opportunity_id' => $opportunityResponse['id'] ?? 'unknown',
        'lead_response' => $response,
        'opportunity_response' => $opportunityResponse,
        'environment' => 'development'
    ]);
} catch (Exception $e) {
    logDevWebhook('crm_error', [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ], false);

    // Em desenvolvimento, simular resposta mesmo com erro
    $simulated_response = simulateCrmResponse($data);
    sendDevWebhookResponse(false, 'Erro no CRM - resposta simulada', $simulated_response);
}

logDevWebhook('webhook_completed', [
    'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
    'memory_peak' => memory_get_peak_usage(true)
], true);
