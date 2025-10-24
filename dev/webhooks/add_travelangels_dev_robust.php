<?php

/**
 * WEBHOOK TRAVELANGELS DEV - VERSÃO ROBUSTA
 * Versão simplificada e mais robusta para lidar com JSON malformado
 */

// Configurações
require_once __DIR__ . '/../config/dev_config.php';

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

// Função para enviar resposta
function sendDevWebhookResponse($success, $message, $data = null)
{
    $response = [
        'success' => $success,
        'message' => $message,
        'environment' => 'development',
        'timestamp' => date('Y-m-d H:i:s'),
        'server' => 'bpsegurosimediato.com.br',
        'api_version' => '2.0-dev'
    ];

    if ($data) {
        $response['data'] = $data;
    }

    logDevWebhook('response_sent', $response, $success);
    
    http_response_code($success ? 200 : 400);
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// Função robusta para extrair dados do JSON malformado
function extractDataFromMalformedJson($json_string)
{
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
        'MARCA' => '/"MARCA":"([^"]+)"/'
    ];
    
    foreach ($patterns as $field => $pattern) {
        if (preg_match($pattern, $json_string, $matches)) {
            $extracted_data[$field] = $matches[1];
        }
    }
    
    return $extracted_data;
}

// Função para criar JSON válido
function createValidJson($extracted_data)
{
    $valid_json = [
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
            'schema' => [
                [
                    'fieldName' => 'NOME',
                    'fieldType' => 'FormTextInput',
                    'fieldElementId' => '97e5c20e-4fe9-8fcf-d941-485bbc20f784'
                ]
            ]
        ]
    ];
    
    return json_encode($valid_json);
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
logDevWebhook('raw_input_received', [
    'length' => strlen($raw_input),
    'preview' => substr($raw_input, 0, 200) . '...'
], true);

// Tentar decodificar JSON
$data = json_decode($raw_input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    logDevWebhook('json_decode_error', [
        'error' => json_last_error_msg(),
        'raw_input_preview' => substr($raw_input, 0, 500) . '...'
    ], false);
    
    // Tentar extrair dados usando regex
    logDevWebhook('attempting_data_extraction', ['method' => 'regex'], true);
    
    $extracted_data = extractDataFromMalformedJson($raw_input);
    
    if (!empty($extracted_data)) {
        logDevWebhook('data_extraction_success', $extracted_data, true);
        
        // Criar JSON válido
        $valid_json = createValidJson($extracted_data);
        $data = json_decode($valid_json, true);
        
        if ($data) {
            logDevWebhook('json_reconstruction_success', ['status' => 'success'], true);
        } else {
            logDevWebhook('json_reconstruction_failed', ['error' => 'Failed to create valid JSON'], false);
            sendDevWebhookResponse(false, 'Erro ao reconstruir JSON');
            exit;
        }
    } else {
        logDevWebhook('data_extraction_failed', ['error' => 'No data extracted'], false);
        sendDevWebhookResponse(false, 'Erro ao extrair dados do JSON malformado');
        exit;
    }
} else {
    logDevWebhook('json_decode_success', ['status' => 'valid_json'], true);
}

logDevWebhook('data_received', $data, true);

// Validar signature do Webflow (API V2)
$signature = $_SERVER['HTTP_X_WEBFLOW_SIGNATURE'] ?? '';
if (!empty($signature)) {
    logDevWebhook('signature_validation', [
        'signature_length' => strlen($signature),
        'signature_preview' => substr($signature, 0, 10) . '...'
    ], true);
} else {
    logDevWebhook('signature_missing', ['warning' => 'No signature provided'], false);
}

// Extrair dados do formulário
$formData = $data['payload']['data'] ?? [];
logDevWebhook('form_data_extracted', $formData, true);

// Verificar se é ambiente de desenvolvimento
if (isset($formData['NOME']) && strpos($formData['NOME'], 'TESTE') !== false) {
    logDevWebhook('test_mode_detected', $formData, true);
    sendDevWebhookResponse(true, 'Modo de teste detectado - dados processados com sucesso', [
        'test_data' => $formData,
        'environment' => 'development'
    ]);
    exit;
}

// Simular criação de lead no EspoCRM
$leadData = [
    'name' => $formData['NOME'] ?? 'Nome não informado',
    'emailAddress' => $formData['Email'] ?? '',
    'phoneNumber' => $formData['CELULAR'] ?? '',
    'source' => 'Site',
    'description' => 'Lead criado via Webflow API V2 - Desenvolvimento',
    'customFields' => [
        'cpf' => $formData['CPF'] ?? '',
        'placa' => $formData['PLACA'] ?? '',
        'cep' => $formData['CEP'] ?? '',
        'ano' => $formData['ANO'] ?? '',
        'marca' => $formData['MARCA'] ?? ''
    ]
];

logDevWebhook('lead_data_prepared', $leadData, true);

// Simular resposta do CRM
$crmResponse = [
    'id' => 'dev_lead_' . uniqid(),
    'name' => $leadData['name'],
    'email' => $leadData['emailAddress'],
    'status' => 'created',
    'environment' => 'development',
    'timestamp' => date('Y-m-d H:i:s')
];

logDevWebhook('crm_response_simulated', $crmResponse, true);

// Enviar resposta de sucesso
sendDevWebhookResponse(true, 'Lead criado com sucesso no ambiente de desenvolvimento', [
    'lead_id' => $crmResponse['id'],
    'crm_response' => $crmResponse,
    'environment' => 'development'
]);

// Log de conclusão
logDevWebhook('webhook_completed', [
    'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
    'memory_peak' => memory_get_peak_usage(true)
], true);

?>
