<?php

/**
 * WEBHOOK OCTADESK DESENVOLVIMENTO
 * dev/webhooks/add_webflow_octa_dev.php
 * 
 * Webhook de desenvolvimento para integração Webflow + OctaDesk
 * Usa o simulador OctaDesk para testes
 */

// Configurações de desenvolvimento
require_once __DIR__ . '/../config/dev_config.php';

// Log específico do webhook
function logDevWebhook($action, $data = null, $success = true)
{
    $logFile = __DIR__ . '/../logs/webhook_octadesk_dev.txt';
    $timestamp = date('Y-m-d H:i:s');
    $status = $success ? 'SUCCESS' : 'ERROR';

    $logEntry = "[{$timestamp}] [{$status}] [OCTADESK-DEV] {$action}";
    if ($data !== null) {
        $logEntry .= " | Data: " . json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    $logEntry .= PHP_EOL;

    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Função para enviar dados para OctaDesk (Simulador)
function sendToOctaDesk($data)
{
    global $DEV_CRM_CONFIG;

    $url = $DEV_CRM_CONFIG['octadesk_api_url'] . '/api/v1/contacts';
    $apiKey = $DEV_CRM_CONFIG['octadesk_api_key'];

    logDevWebhook('octadesk_request', [
        'url' => $url,
        'api_key_length' => strlen($apiKey),
        'data' => $data
    ], true);

    $ch = curl_init();

    $headers = [
        'X-Api-Key: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $result = [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
        'success' => $httpCode >= 200 && $httpCode < 300
    ];

    logDevWebhook('octadesk_response', [
        'http_code' => $httpCode,
        'response_length' => strlen($response),
        'success' => $result['success']
    ], $result['success']);

    return $result;
}

// Função para criar conversa no OctaDesk
function createOctaDeskConversation($contactId, $subject)
{
    global $DEV_CRM_CONFIG;

    $url = $DEV_CRM_CONFIG['octadesk_api_url'] . '/api/v1/conversations';
    $apiKey = $DEV_CRM_CONFIG['octadesk_api_key'];

    $data = [
        'contact_id' => $contactId,
        'subject' => $subject,
        'status' => 'open'
    ];

    logDevWebhook('octadesk_conversation_request', [
        'url' => $url,
        'data' => $data
    ], true);

    $ch = curl_init();

    $headers = [
        'X-Api-Key: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $result = [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
        'success' => $httpCode >= 200 && $httpCode < 300
    ];

    logDevWebhook('octadesk_conversation_response', [
        'http_code' => $httpCode,
        'success' => $result['success']
    ], $result['success']);

    return $result;
}

// Função para enviar mensagem no OctaDesk
function sendOctaDeskMessage($conversationId, $message)
{
    global $DEV_CRM_CONFIG;

    $url = $DEV_CRM_CONFIG['octadesk_api_url'] . '/api/v1/messages';
    $apiKey = $DEV_CRM_CONFIG['octadesk_api_key'];

    $data = [
        'conversation_id' => $conversationId,
        'message' => $message,
        'type' => 'text',
        'sender' => 'system'
    ];

    logDevWebhook('octadesk_message_request', [
        'url' => $url,
        'data' => $data
    ], true);

    $ch = curl_init();

    $headers = [
        'X-Api-Key: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $result = [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
        'success' => $httpCode >= 200 && $httpCode < 300
    ];

    logDevWebhook('octadesk_message_response', [
        'http_code' => $httpCode,
        'success' => $result['success']
    ], $result['success']);

    return $result;
}

// Função para validar assinatura Webflow
function validateWebflowSignature($payload, $signature, $secret)
{
    $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    return hash_equals($expectedSignature, $signature);
}

// Função principal do webhook
function processWebflowWebhook()
{
    global $WEBFLOW_SECRET_KEY;

    // Obter dados da requisição
    $input = file_get_contents('php://input');
    $headers = getallheaders();

    logDevWebhook('webhook_received', [
        'method' => $_SERVER['REQUEST_METHOD'],
        'headers' => $headers,
        'input_length' => strlen($input)
    ], true);

    // Validar método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        logDevWebhook('invalid_method', ['method' => $_SERVER['REQUEST_METHOD']], false);
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    // Validar assinatura Webflow
    $signature = $headers['X-Webflow-Signature'] ?? '';
    if (!validateWebflowSignature($input, $signature, $WEBFLOW_SECRET_KEY)) {
        logDevWebhook('invalid_signature', [
            'signature' => $signature,
            'expected_length' => strlen($signature)
        ], false);
        http_response_code(401);
        echo json_encode(['error' => 'Invalid signature']);
        return;
    }

    // Parse dos dados
    $data = json_decode($input, true);
    if (!$data) {
        logDevWebhook('invalid_json', ['input' => $input], false);
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        return;
    }

    logDevWebhook('webflow_data_parsed', $data, true);

    // Extrair dados do formulário
    $formData = $data['data'] ?? [];
    $formName = $data['name'] ?? 'Formulário Desconhecido';

    // Mapear campos do formulário
    $contactData = [
        'name' => $formData['name'] ?? $formData['nome'] ?? '',
        'email' => $formData['email'] ?? $formData['e-mail'] ?? '',
        'phone' => $formData['phone'] ?? $formData['telefone'] ?? $formData['celular'] ?? '',
        'tags' => ['webflow', 'dev', 'formulario'],
        'custom_fields' => [
            'source' => 'Webflow Dev',
            'form_name' => $formName,
            'submission_id' => $data['_id'] ?? '',
            'site_id' => $data['site'] ?? '',
            'form_data' => json_encode($formData)
        ]
    ];

    logDevWebhook('contact_data_mapped', $contactData, true);

    // Enviar para OctaDesk (Simulador)
    $octaResult = sendToOctaDesk($contactData);

    if ($octaResult['success']) {
        $responseData = json_decode($octaResult['response'], true);
        $contactId = $responseData['data']['id'] ?? null;

        if ($contactId) {
            // Criar conversa
            $conversationResult = createOctaDeskConversation(
                $contactId,
                "Novo lead do formulário: {$formName}"
            );

            if ($conversationResult['success']) {
                $convResponseData = json_decode($conversationResult['response'], true);
                $conversationId = $convResponseData['data']['id'] ?? null;

                if ($conversationId) {
                    // Enviar mensagem inicial
                    $message = "Novo lead recebido via Webflow!\n\n";
                    $message .= "Formulário: {$formName}\n";
                    $message .= "Nome: " . ($contactData['name'] ?: 'Não informado') . "\n";
                    $message .= "Email: " . ($contactData['email'] ?: 'Não informado') . "\n";
                    $message .= "Telefone: " . ($contactData['phone'] ?: 'Não informado') . "\n";
                    $message .= "\nDados completos: " . json_encode($formData, JSON_UNESCAPED_UNICODE);

                    sendOctaDeskMessage($conversationId, $message);
                }
            }
        }

        logDevWebhook('webhook_success', [
            'contact_id' => $contactId,
            'form_name' => $formName
        ], true);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Webhook processado com sucesso',
            'contact_id' => $contactId,
            'simulator' => 'OctaDesk-Dev'
        ]);
    } else {
        logDevWebhook('webhook_error', [
            'http_code' => $octaResult['http_code'],
            'error' => $octaResult['error'],
            'response' => $octaResult['response']
        ], false);

        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Erro ao processar webhook',
            'details' => $octaResult['error']
        ]);
    }
}

// Executar webhook
try {
    processWebflowWebhook();
} catch (Exception $e) {
    logDevWebhook('webhook_exception', [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], false);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro interno do webhook'
    ]);
}
