<?php
/**
 * WEBHOOK OCTADESK - AMBIENTE DE DESENVOLVIMENTO
 * bpsegurosimediato.com.br/dev/webhooks/add_webflow_octa.php
 * 
 * Versão de desenvolvimento com API V2, logging avançado e validação de signature
 * Baseado no webhook de produção mas com funcionalidades específicas para testes
 */

// Incluir configuração de desenvolvimento
require_once '/var/www/html/dev_config.php';

// Verificar se é ambiente de desenvolvimento
if (!$is_dev) {
    http_response_code(403);
    exit('Acesso negado: Este endpoint é apenas para desenvolvimento');
}

// Configurações específicas do webhook de desenvolvimento
$WEBFLOW_SECRET_OCTADESK = $DEV_WEBFLOW_SECRETS['octadesk'];
$DEBUG_LOG_FILE = $DEV_LOGGING['octadesk'];
$LOG_PREFIX = '[DEV-OCTADESK] ';

// Headers de resposta para desenvolvimento
header('Content-Type: application/json; charset=utf-8');
header('X-Environment: development');
header('X-API-Version: 2.0-dev');
header('X-Webhook: octadesk-dev');

// Configurações de log para desenvolvimento
$MAX_LOG_SIZE = 1024 * 1024; // 1MB em dev
$LOG_BACKUPS = 3;

// Função para log específico de desenvolvimento
function logDevWebhook($event, $data, $success = true) {
    global $DEBUG_LOG_FILE, $LOG_PREFIX, $is_dev;
    
    if (!$is_dev) return;
    
    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'environment' => 'development',
        'webhook' => 'octadesk',
        'event' => $event,
        'success' => $success,
        'data' => $data,
        'request_id' => uniqid('dev_octa_', true),
        'memory_usage' => memory_get_usage(true),
        'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
    ];
    
    $log_entry = $LOG_PREFIX . json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
    file_put_contents($DEBUG_LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);
}

// Função para validar signature da API V2 (com fallback para dev)
function validateWebflowSignatureDev($payload, $signature, $timestamp, $secret) {
    global $is_dev;
    
    // Em desenvolvimento, permitir fallback se signature falhar
    if ($is_dev && (empty($signature) || empty($timestamp))) {
        logDevWebhook('signature_warning', 'Headers de signature não encontrados - continuando em modo dev');
        return true; // Permitir em desenvolvimento
    }
    
    // Validação normal da signature
    $expected_signature = hash_hmac('sha256', $timestamp . $payload, $secret);
    $is_valid = hash_equals($expected_signature, $signature);
    
    if (!$is_valid && $is_dev) {
        logDevWebhook('signature_fallback', 'Signature inválida - usando fallback de desenvolvimento');
        return true; // Fallback em desenvolvimento
    }
    
    return $is_valid;
}

// Função para enviar resposta padronizada
function sendDevWebhookResponse($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message,
        'environment' => 'development',
        'timestamp' => date('Y-m-d H:i:s'),
        'server' => 'bpsegurosimediato.com.br',
        'api_version' => '2.0-dev'
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    logDevWebhook('response_sent', $response, $success);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// Função para processar dados de teste
function processTestData($data) {
    global $DEV_TEST_DATA;
    
    // Se for dados de teste, não enviar para OctaDesk real
    if (isset($data['test_mode']) || strpos($data['email'] ?? '', '@dev.com') !== false) {
        logDevWebhook('test_data_processed', [
            'original_data' => $data,
            'test_mode' => true,
            'octadesk_bypass' => true
        ], true);
        
        return [
            'success' => true,
            'message' => 'Dados de teste processados - não enviado para OctaDesk',
            'test_mode' => true
        ];
    }
    
    return null; // Processar normalmente
}

// Função para simular resposta do OctaDesk em desenvolvimento
function simulateOctaDeskResponse($data) {
    return [
        'success' => true,
        'message' => 'Simulação de resposta do OctaDesk em desenvolvimento',
        'ticket_id' => 'dev_ticket_' . uniqid(),
        'octadesk_response' => 'Simulado',
        'data' => $data
    ];
}

// Função para mascarar dados sensíveis (versão de desenvolvimento)
function mask_val_dev($key, $val) {
    $sensitive_keys = ['email', 'phone', 'cpf', 'cnpj', 'password'];
    
    if (in_array(strtolower($key), $sensitive_keys)) {
        if (strlen($val) > 4) {
            return substr($val, 0, 2) . str_repeat('*', strlen($val) - 4) . substr($val, -2);
        }
        return str_repeat('*', strlen($val));
    }
    
    return $val;
}

// Função para rotação de logs (versão de desenvolvimento)
function log_rotate_if_needed_dev($log_file, $max_size, $backups) {
    if (file_exists($log_file) && filesize($log_file) > $max_size) {
        // Rotacionar logs
        for ($i = $backups - 1; $i >= 1; $i--) {
            $old_file = $log_file . '.' . $i;
            $new_file = $log_file . '.' . ($i + 1);
            if (file_exists($old_file)) {
                rename($old_file, $new_file);
            }
        }
        
        if (file_exists($log_file)) {
            rename($log_file, $log_file . '.1');
        }
    }
}

// ===== INÍCIO DO PROCESSAMENTO =====

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

// Obter dados do payload
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    logDevWebhook('json_error', ['error' => json_last_error_msg(), 'payload' => $payload], false);
    sendDevWebhookResponse(false, 'Erro ao decodificar JSON');
    exit;
}

// Obter headers de signature da API V2
$signature = $_SERVER['HTTP_X_WEBFLOW_SIGNATURE'] ?? '';
$timestamp = $_SERVER['HTTP_X_WEBFLOW_TIMESTAMP'] ?? '';

logDevWebhook('signature_headers', [
    'signature_present' => !empty($signature),
    'timestamp_present' => !empty($timestamp),
    'signature_length' => strlen($signature),
    'timestamp_value' => $timestamp
], true);

// Validar signature (com fallback para desenvolvimento)
if (!validateWebflowSignatureDev($payload, $signature, $timestamp, $WEBFLOW_SECRET_OCTADESK)) {
    logDevWebhook('signature_invalid', [
        'signature' => $signature,
        'timestamp' => $timestamp,
        'payload_length' => strlen($payload)
    ], false);
    sendDevWebhookResponse(false, 'Signature inválida');
    exit;
}

logDevWebhook('signature_valid', ['validation' => 'passed'], true);

// Verificar se são dados de teste
$test_result = processTestData($data);
if ($test_result) {
    sendDevWebhookResponse(true, $test_result['message'], $test_result);
    exit;
}

// Processar dados do ticket
$ticket_data = [
    'name' => $data['name'] ?? 'Nome não informado',
    'email' => $data['email'] ?? '',
    'phone' => $data['phone'] ?? '',
    'subject' => $data['subject'] ?? 'Contato via Webflow Dev',
    'message' => $data['message'] ?? 'Mensagem enviada do ambiente de desenvolvimento',
    'source' => 'Webflow Dev',
    'environment' => 'development'
];

logDevWebhook('ticket_data_prepared', $ticket_data, true);

// Simular criação de ticket no OctaDesk
try {
    // Em desenvolvimento, simular resposta do OctaDesk
    $simulated_response = simulateOctaDeskResponse($ticket_data);
    
    logDevWebhook('octadesk_response', [
        'status' => 'simulated',
        'response' => $simulated_response
    ], true);
    
    sendDevWebhookResponse(true, 'Ticket criado com sucesso no ambiente de desenvolvimento', [
        'ticket_id' => $simulated_response['ticket_id'],
        'octadesk_response' => $simulated_response,
        'environment' => 'development'
    ]);
    
} catch (Exception $e) {
    logDevWebhook('octadesk_error', [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ], false);
    
    // Em desenvolvimento, simular resposta mesmo com erro
    $simulated_response = simulateOctaDeskResponse($ticket_data);
    sendDevWebhookResponse(false, 'Erro no OctaDesk - resposta simulada', $simulated_response);
}

logDevWebhook('webhook_completed', [
    'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
    'memory_peak' => memory_get_peak_usage(true)
], true);
?>

