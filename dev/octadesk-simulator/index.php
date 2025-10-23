<?php

/**
 * SIMULADOR OCTADESK PARA DESENVOLVIMENTO
 * dev/octadesk-simulator/index.php
 * 
 * Ambiente de simulação completo do OctaDesk para testes de desenvolvimento
 * Sem alterações no servidor de produção
 */

// Configurações do simulador
$SIMULATOR_CONFIG = [
    'name' => 'OctaDesk Simulator',
    'version' => '1.0.0',
    'environment' => 'development',
    'base_url' => 'https://bpsegurosimediato.com.br/dev/octadesk-simulator',
    'api_version' => 'v1',
    'log_file' => __DIR__ . '/../logs/octadesk_simulator.txt',
    'data_file' => __DIR__ . '/data/simulator_data.json'
];

// Headers padrão
header('Content-Type: application/json; charset=utf-8');
header('X-Simulator: OctaDesk-Dev');
header('X-Version: 1.0.0');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Api-Key');

// Tratar OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Função para log do simulador
function logSimulator($level, $message, $data = null)
{
    global $SIMULATOR_CONFIG;

    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] [{$level}] {$message}";

    if ($data !== null) {
        $logEntry .= " | Data: " . json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    $logEntry .= PHP_EOL;

    file_put_contents($SIMULATOR_CONFIG['log_file'], $logEntry, FILE_APPEND | LOCK_EX);
}

// Função para gerar ID único
function generateUniqueId($prefix = 'sim')
{
    return $prefix . '_' . uniqid() . '_' . substr(md5(microtime()), 0, 8);
}

// Função para resposta padronizada
function sendResponse($httpCode, $data, $message = null)
{
    http_response_code($httpCode);

    $response = [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'timestamp' => date('c'),
        'simulator' => 'OctaDesk-Dev'
    ];

    if ($message) {
        $response['message'] = $message;
    }

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// Função para validar API Key
function validateApiKey($apiKey)
{
    // API Keys válidas para simulação
    $validKeys = [
        'dev_octadesk_key_12345',
        'test_octadesk_key_67890',
        'simulator_octadesk_key'
    ];

    return in_array($apiKey, $validKeys);
}

// Função para obter API Key do header
function getApiKey()
{
    $headers = getallheaders();

    if (isset($headers['X-Api-Key'])) {
        return $headers['X-Api-Key'];
    }

    if (isset($headers['Authorization'])) {
        $auth = $headers['Authorization'];
        if (strpos($auth, 'Bearer ') === 0) {
            return substr($auth, 7);
        }
    }

    return null;
}

// Função para carregar dados do simulador
function loadSimulatorData()
{
    global $SIMULATOR_CONFIG;

    if (file_exists($SIMULATOR_CONFIG['data_file'])) {
        $data = json_decode(file_get_contents($SIMULATOR_CONFIG['data_file']), true);
        return $data ?: ['contacts' => [], 'conversations' => [], 'messages' => []];
    }

    return ['contacts' => [], 'conversations' => [], 'messages' => []];
}

// Função para salvar dados do simulador
function saveSimulatorData($data)
{
    global $SIMULATOR_CONFIG;

    // Criar diretório se não existir
    $dir = dirname($SIMULATOR_CONFIG['data_file']);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($SIMULATOR_CONFIG['data_file'], json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// Log de início da requisição
logSimulator('INFO', 'Requisição recebida', [
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'headers' => getallheaders()
]);

// Validar API Key
$apiKey = getApiKey();
if (!$apiKey || !validateApiKey($apiKey)) {
    logSimulator('ERROR', 'API Key inválida', ['api_key' => $apiKey]);
    sendResponse(401, null, 'API Key inválida ou ausente');
    exit;
}

// Obter dados da requisição
$input = file_get_contents('php://input');
$requestData = json_decode($input, true);

// Parse da URL
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Remover partes desnecessárias da URL
$apiIndex = array_search('api', $pathParts);
if ($apiIndex !== false) {
    $pathParts = array_slice($pathParts, $apiIndex + 1);
}

// Determinar endpoint
$endpoint = implode('/', $pathParts);
$method = $_SERVER['REQUEST_METHOD'];

logSimulator('INFO', 'Processando endpoint', [
    'endpoint' => $endpoint,
    'method' => $method,
    'data' => $requestData
]);

// Carregar dados existentes
$simulatorData = loadSimulatorData();

// Roteamento de endpoints
try {
    switch ($endpoint) {
        case 'v1/contacts':
            if ($method === 'POST') {
                handleCreateContact($requestData, $simulatorData);
            } elseif ($method === 'GET') {
                handleListContacts($simulatorData);
            } else {
                sendResponse(405, null, 'Método não permitido');
            }
            break;

        case 'v1/conversations':
            if ($method === 'POST') {
                handleCreateConversation($requestData, $simulatorData);
            } elseif ($method === 'GET') {
                handleListConversations($simulatorData);
            } else {
                sendResponse(405, null, 'Método não permitido');
            }
            break;

        case 'v1/messages':
            if ($method === 'POST') {
                handleSendMessage($requestData, $simulatorData);
            } else {
                sendResponse(405, null, 'Método não permitido');
            }
            break;

        case 'v1/health':
        case 'health':
            handleHealthCheck();
            break;

        case 'v1/info':
        case 'info':
            handleInfo();
            break;

        default:
            sendResponse(404, null, 'Endpoint não encontrado');
            break;
    }
} catch (Exception $e) {
    logSimulator('ERROR', 'Erro no simulador', [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);

    sendResponse(500, null, 'Erro interno do simulador');
}

// Função para criar contato
function handleCreateContact($data, &$simulatorData)
{
    logSimulator('INFO', 'Criando contato', $data);

    // Validações básicas
    if (empty($data['name']) && empty($data['email']) && empty($data['phone'])) {
        sendResponse(400, null, 'Nome, email ou telefone é obrigatório');
        return;
    }

    $contact = [
        'id' => generateUniqueId('contact'),
        'name' => $data['name'] ?? '',
        'email' => $data['email'] ?? '',
        'phone' => $data['phone'] ?? '',
        'created_at' => date('c'),
        'updated_at' => date('c'),
        'status' => 'active',
        'tags' => $data['tags'] ?? [],
        'custom_fields' => $data['custom_fields'] ?? []
    ];

    $simulatorData['contacts'][] = $contact;
    saveSimulatorData($simulatorData);

    logSimulator('SUCCESS', 'Contato criado', ['contact_id' => $contact['id']]);
    sendResponse(201, $contact, 'Contato criado com sucesso');
}

// Função para listar contatos
function handleListContacts($simulatorData)
{
    logSimulator('INFO', 'Listando contatos');

    $contacts = $simulatorData['contacts'] ?? [];

    sendResponse(200, [
        'contacts' => $contacts,
        'total' => count($contacts)
    ], 'Contatos listados com sucesso');
}

// Função para criar conversa
function handleCreateConversation($data, &$simulatorData)
{
    logSimulator('INFO', 'Criando conversa', $data);

    $conversation = [
        'id' => generateUniqueId('conv'),
        'contact_id' => $data['contact_id'] ?? null,
        'subject' => $data['subject'] ?? 'Nova conversa',
        'status' => 'open',
        'created_at' => date('c'),
        'updated_at' => date('c'),
        'messages' => []
    ];

    $simulatorData['conversations'][] = $conversation;
    saveSimulatorData($simulatorData);

    logSimulator('SUCCESS', 'Conversa criada', ['conversation_id' => $conversation['id']]);
    sendResponse(201, $conversation, 'Conversa criada com sucesso');
}

// Função para listar conversas
function handleListConversations($simulatorData)
{
    logSimulator('INFO', 'Listando conversas');

    $conversations = $simulatorData['conversations'] ?? [];

    sendResponse(200, [
        'conversations' => $conversations,
        'total' => count($conversations)
    ], 'Conversas listadas com sucesso');
}

// Função para enviar mensagem
function handleSendMessage($data, &$simulatorData)
{
    logSimulator('INFO', 'Enviando mensagem', $data);

    if (empty($data['conversation_id']) || empty($data['message'])) {
        sendResponse(400, null, 'ID da conversa e mensagem são obrigatórios');
        return;
    }

    $message = [
        'id' => generateUniqueId('msg'),
        'conversation_id' => $data['conversation_id'],
        'message' => $data['message'],
        'type' => $data['type'] ?? 'text',
        'sender' => $data['sender'] ?? 'system',
        'created_at' => date('c')
    ];

    $simulatorData['messages'][] = $message;
    saveSimulatorData($simulatorData);

    logSimulator('SUCCESS', 'Mensagem enviada', ['message_id' => $message['id']]);
    sendResponse(201, $message, 'Mensagem enviada com sucesso');
}

// Função para health check
function handleHealthCheck()
{
    sendResponse(200, [
        'status' => 'healthy',
        'simulator' => 'OctaDesk-Dev',
        'version' => '1.0.0',
        'timestamp' => date('c')
    ], 'Simulador funcionando');
}

// Função para informações do simulador
function handleInfo()
{
    global $SIMULATOR_CONFIG;

    sendResponse(200, [
        'name' => $SIMULATOR_CONFIG['name'],
        'version' => $SIMULATOR_CONFIG['version'],
        'environment' => $SIMULATOR_CONFIG['environment'],
        'base_url' => $SIMULATOR_CONFIG['base_url'],
        'api_version' => $SIMULATOR_CONFIG['api_version'],
        'endpoints' => [
            'POST /api/v1/contacts' => 'Criar contato',
            'GET /api/v1/contacts' => 'Listar contatos',
            'POST /api/v1/conversations' => 'Criar conversa',
            'GET /api/v1/conversations' => 'Listar conversas',
            'POST /api/v1/messages' => 'Enviar mensagem',
            'GET /api/v1/health' => 'Health check',
            'GET /api/v1/info' => 'Informações do simulador'
        ]
    ], 'Informações do simulador');
}

logSimulator('INFO', 'Requisição processada com sucesso');
