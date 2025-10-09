<?php
/**
 * add_collect_octa.php — Collect.chat -> Octadesk (send-template)
 *
 * NOVA ABORDAGEM: Usar API /contacts para salvar custom fields (como no add_webflow_octa)
 * Depois usar send-template apenas para enviar a mensagem
 * 
 * VERSÃO: Produção 2022-08-25
 * STATUS: ✅ FUNCIONANDO - Custom fields salvos via API /contacts
 * LOGGING: ✅ ADICIONADO para debug em produção
 */

// Configurações
$OCTADESK_API_KEY = 'b4e081fa-94ab-4456-8378-991bf995d3ea.d3e8e579-869d-4973-b34d-82391d08702b';
$API_BASE = 'https://o205242-d60.api004.octadesk.services';
$URL_CONTACTS = $API_BASE . '/contacts';
$URL_SEND_TPL = $API_BASE . '/chat/conversation/send-template';
$OCTADESK_FROM = '+551132301422';

// Configurações de Log
$DEBUG_LOG_FILE = __DIR__ . '/octa_collect_webhook.log';
$MAX_LOG_SIZE = 2 * 1024 * 1024; // 2MB
$LOG_BACKUPS = 5;

// ==================== SISTEMA DE LOG ====================

function log_rotate_if_needed($file, $maxSize, $backups) {
    if (!file_exists($file) || @filesize($file) < $maxSize) return;
    for ($i = $backups - 1; $i >= 1; $i--) {
        $src = $file . '.' . $i;
        $dst = $file . '.' . ($i + 1);
        if (file_exists($src)) @rename($src, $dst);
    }
    @rename($file, $file . '.1');
}

function mask_val($k, $v) {
    $kLow = strtolower($k);
    if (!is_string($v)) return $v;
    if (str_contains($kLow,'api') || str_contains($kLow,'token') || str_contains($kLow,'key')) return '***MASKED***';
    if (str_contains($kLow,'cpf')) {
        $d = preg_replace('/\D+/','',$v);
        return (strlen($d)===11) ? substr($d,0,3).'.***.***-'.substr($d,-2) : '***MASKED***';
    }
    if (str_contains($kLow,'cep')) {
        $d = preg_replace('/\D+/','',$v);
        return (strlen($d)===8) ? substr($d,0,2).'***-**'.substr($d,-1) : '***MASKED***';
    }
    if (str_contains($kLow,'placa')) {
        $s = preg_replace('/[^A-Z0-9-]/i','',$v);
        return (strlen($s)>=3) ? substr($s,0,3).'-****' : '***MASKED***';
    }
    if (str_contains($kLow,'email')) {
        $parts = explode('@', $v);
        if (count($parts)===2) {
            $u = $parts[0]; $d = $parts[1];
            $uMask = strlen($u)>2 ? substr($u,0,1) . str_repeat('*', max(1, strlen($u)-2)) . substr($u,-1) : '***';
            return $uMask . '@' . $d;
        }
        return '***MASKED***';
    }
    if (str_contains($kLow,'number') || str_contains($kLow,'telefone') || str_contains($kLow,'phone')) {
        $d = preg_replace('/\D+/','',$v);
        return (strlen($d)>4) ? substr($d,0,4).str_repeat('*',max(0,strlen($d)-6)).substr($d,-2) : '***MASKED***';
    }
    return $v;
}

function log_step($title, $data = null) {
    global $DEBUG_LOG_FILE, $MAX_LOG_SIZE, $LOG_BACKUPS;
    try {
        log_rotate_if_needed($DEBUG_LOG_FILE, $MAX_LOG_SIZE, $LOG_BACKUPS);
        $ts = date('c');
        $meth = $_SERVER['REQUEST_METHOD'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $line = "[$ts] [$meth $uri] $title";
        if ($data !== null) {
            if (is_array($data)) {
                $safe = $data;
                array_walk_recursive($safe, function (&$v, $k) { $v = mask_val($k, $v); });
                $line .= ' | ' . json_encode($safe, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            } else {
                $line .= ' | ' . (string)$data;
            }
        }
        file_put_contents($DEBUG_LOG_FILE, $line . PHP_EOL, FILE_APPEND);
    } catch (\Throwable $e) {
        // Não quebrar o fluxo por erro de log
    }
}

// ==================== INÍCIO DO PROCESSAMENTO ====================

log_step('=== INÍCIO DO PROCESSAMENTO ===');

// Receber dados do Collect.chat
$data = $_POST;
$raw = file_get_contents('php://input') ?: '';

log_step('Dados recebidos', [
    'POST' => $data,
    'RAW' => $raw,
    'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? '',
    'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'REFERER' => $_SERVER['HTTP_REFERER'] ?? ''
]);

// Se não tem dados POST, tentar parse JSON
if (empty($data) && !empty($raw)) {
    $jsonData = json_decode($raw, true);
    if (is_array($jsonData)) {
        $data = $jsonData;
        log_step('Dados parseados do JSON', $data);
    }
}

// Extrair campos
$nome        = trim($data['NAME']     ?? $data['NOME']    ?? $data['nome']    ?? '');
$telefone    = trim($data['NUMBER']   ?? $data['TELEFONE'] ?? $data['telefone'] ?? '');
$email       = trim($data['EMAIL']    ?? $data['email']   ?? '');
$cep         = trim($data['CEP']      ?? $data['cep']     ?? '');
$placa       = trim($data['PLACA']    ?? $data['placa']   ?? '');
$cpf         = trim($data['CPF']      ?? $data['cpf']     ?? '');
$produto     = trim($data['produto']  ?? $data['PRODUTO'] ?? $data['produto'] ?? '');
$gclid       = trim($data['gclid']    ?? $data['GCLID']   ?? $data['gclid'] ?? '');

// Extrair GCLID da URL se disponível
if (empty($gclid) && !empty($data['page_address'])) {
    $pageUrl = $data['page_address'];
    $query = parse_url($pageUrl, PHP_URL_QUERY);
    if ($query) {
        parse_str($query, $queryParams);
        $gclid = $queryParams['gclid'] ?? '';
    }
}

log_step('Campos extraídos', [
    'nome' => $nome,
    'telefone' => $telefone,
    'email' => $email,
    'cep' => $cep,
    'placa' => $placa,
    'cpf' => $cpf,
    'produto' => $produto,
    'gclid' => $gclid
]);

// Validar campos obrigatórios
if (empty($nome) || empty($telefone)) {
    log_step('ERRO: Campos obrigatórios vazios', ['nome' => $nome, 'telefone' => $telefone]);
    http_response_code(400);
    echo json_encode(['error' => 'Nome e telefone são obrigatórios']);
    exit;
}

// Normalizar telefone para E.164
$telefoneE164 = normalizePhoneToE164($telefone);
$telefoneLocal = normalizePhoneToLocal($telefone);

log_step('Telefone normalizado', [
    'original' => $telefone,
    'E164' => $telefoneE164,
    'local' => $telefoneLocal
]);

// Preparar custom fields
$customFields = [];
if (!empty($email)) $customFields['email'] = $email;
if (!empty($cep)) $customFields['cep'] = $cep;
if (!empty($placa)) $customFields['placa'] = $placa;
if (!empty($cpf)) $customFields['cpf'] = $cpf;
if (!empty($gclid)) $customFields['gclid'] = $gclid;

log_step('Custom fields preparados', $customFields);

// PASSO 1: Criar/atualizar contato via API /contacts (para salvar custom fields)
log_step('PASSO 1: Iniciando upsertContactWithCF');
$contactId = upsertContactWithCF($telefoneLocal, $nome, $email, $customFields);

if (!$contactId) {
    log_step('ERRO: Falha ao criar/atualizar contato');
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao criar/atualizar contato']);
    exit;
}

log_step('Contato criado/atualizado com sucesso', ['contact_id' => $contactId]);

// PASSO 2: Enviar mensagem via API send-template
$payload = [
    'target' => [
        'contact' => [
            'phoneContact' => ['number' => $telefoneE164],
            'email' => $email ?: null
        ],
        'tags' => array_values(array_filter(['lead-site', $produto ? "produto:$produto" : null]))
    ],
    'content' => [
        'templateMessage' => [
            'code' => 'site_cotacao',
            'language' => 'pt_BR',
            'components' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $nome ?: 'Cliente']
                    ]
                ]
            ]
        ]
    ],
    'origin' => [
        'from' => ['number' => $OCTADESK_FROM]
    ],
    'options' => [
        'automaticAssign' => true
    ],
    'metadata' => [
        'campaign' => 'collect-chat',
        'utm_source' => 'collect-chat',
        'utm_campaign' => 'site-cotacao',
        'landing_url' => $_SERVER['HTTP_REFERER'] ?? 'https://collect.chat'
    ]
];

log_step('PASSO 2: Payload para send-template preparado', $payload);

// Enviar mensagem
log_step('Enviando mensagem via send-template...');
$response = sendTemplateMessage($payload);

log_step('Resposta do send-template', $response);

if ($response['success']) {
    log_step('SUCESSO: Fluxo completo executado', [
        'contact_id' => $contactId,
        'message' => 'Contato criado e mensagem enviada com sucesso'
    ]);
    
    echo json_encode([
        'success' => true,
        'contact_id' => $contactId,
        'message' => 'Contato criado e mensagem enviada com sucesso'
    ]);
} else {
    log_step('AVISO: Contato criado mas erro ao enviar mensagem', [
        'contact_id' => $contactId,
        'error' => $response['error']
    ]);
    
    echo json_encode([
        'success' => false,
        'contact_id' => $contactId,
        'error' => 'Contato criado mas erro ao enviar mensagem: ' . $response['error']
    ]);
}

log_step('=== FIM DO PROCESSAMENTO ===');

// ==================== FUNÇÕES AUXILIARES ====================

/**
 * Normaliza telefone para formato E.164 (+55...)
 */
function normalizePhoneToE164($phone) {
    $digits = preg_replace('/\D/', '', $phone);
    
    // Se já tem +55, retorna como está
    if (strpos($phone, '+55') === 0) {
        return $phone;
    }
    
    // Se tem 11 dígitos (DDD + 9 dígitos), adiciona +55
    if (strlen($digits) == 11) {
        return '+55' . $digits;
    }
    
    // Se tem 10 dígitos (DDD + 8 dígitos), adiciona +55
    if (strlen($digits) == 10) {
        return '+55' . $digits;
    }
    
    // Se tem menos dígitos, assume que é incompleto
    return $phone;
}

/**
 * Normaliza telefone para formato local (DDD + linha, sem +55)
 */
function normalizePhoneToLocal($phone) {
    $digits = preg_replace('/\D/', '', $phone);
    
    // Remove +55 se presente
    if (strpos($phone, '+55') === 0) {
        $digits = substr($digits, 2);
    }
    
    // Retorna apenas os dígitos locais
    return $digits;
}

/**
 * Função para criar/atualizar contato via API /contacts (igual ao add_webflow_octa)
 */
function upsertContactWithCF($localDigits, $nome, $email = null, $customObj = []) {
    global $URL_CONTACTS, $OCTADESK_API_KEY;
    
    log_step('upsertContactWithCF iniciado', [
        'localDigits' => $localDigits,
        'nome' => $nome,
        'email' => $email,
        'customObj' => $customObj
    ]);
    
    // Buscar contato existente por telefone
    $id = findContactIdByPhoneLocal($localDigits);
    if (!$id && $email) { 
        $id = findContactIdByEmail($email); 
    }
    
    log_step('Contato encontrado', ['id' => $id, 'por_telefone' => !empty($localDigits), 'por_email' => !empty($email)]);
    
    // Preparar payload base
    $payloadBase = ['name' => ($nome ?: 'Cliente')];
    if ($email) $payloadBase['email'] = trim(strtolower($email));
    
    // Preparar custom fields
    if (!empty($customObj)) {
        $customFieldsArray = [];
        foreach ($customObj as $key => $value) {
            $customFieldsArray[] = ['key' => $key, 'value' => $value];
        }
        $payloadBase['customFields'] = $customFieldsArray;
    }
    
    if ($id) {
        // Atualizar contato existente
        log_step('Atualizando contato existente', ['id' => $id, 'payload' => $payloadBase]);
        
        $payload = $payloadBase;
        if (!empty($customObj)) {
            $customFieldsArray = [];
            foreach ($customObj as $key => $value) {
                $customFieldsArray[] = ['key' => $key, 'value' => $value];
            }
            $payload['customFields'] = $customFieldsArray;
        }
        
        $response = octaRequest('PATCH', $URL_CONTACTS . '/' . urlencode($id), $payload);
        log_step('Resposta PATCH', $response);
        
        if ($response['http_code'] >= 200 && $response['http_code'] < 300) {
            log_step('Contato atualizado com sucesso', ['id' => $id]);
            return $id;
        }
        log_step('AVISO: PATCH falhou mas retornando ID', ['id' => $id, 'response' => $response]);
        return $id; // Retorna mesmo com erro
    }
    
    // Criar novo contato
    log_step('Criando novo contato');
    
    $payloadCreate = $payloadBase;
    if (!empty($customObj)) {
        $customFieldsArray = [];
        foreach ($customObj as $key => $value) {
            $customFieldsArray[] = ['key' => $key, 'value' => $value];
        }
        $payloadCreate['customFields'] = $customFieldsArray;
    }
    
    // Adicionar phoneContacts
    if ($localDigits) {
        $payloadCreate['phoneContacts'] = [
            [
                'number' => $localDigits,
                'countryCode' => '55',
                'type' => 1  // 1 = Cell (Celular)
            ]
        ];
    }
    
    log_step('Payload para criação', $payloadCreate);
    
    $response = octaRequest('POST', $URL_CONTACTS, $payloadCreate);
    log_step('Resposta POST criação', $response);
    
    $newId = null;
    
    if ($response['http_code'] >= 200 && $response['http_code'] < 300) {
        $jsonResponse = json_decode($response['body'], true);
        $newId = $jsonResponse['id'] ?? $jsonResponse['_id'] ?? null;
        
        if ($newId) {
            log_step('Novo contato criado com sucesso', ['id' => $newId]);
            return $newId;
        }
    }
    
    // 409 por e-mail -> buscar por e-mail e PATCH
    if ($response['http_code'] == 409 && $email) {
        log_step('Conflito 409 - buscando por email', ['email' => $email]);
        
        $idByEmail = findContactIdByEmail($email);
        if ($idByEmail) {
            log_step('Contato encontrado por email', ['id' => $idByEmail]);
            
            $payload = $payloadBase;
            if (!empty($customObj)) {
                $customFieldsArray = [];
                foreach ($customObj as $key => $value) {
                    $customFieldsArray[] = ['key' => $key, 'value' => $value];
                }
                $payload['customFields'] = $customFieldsArray;
            }
            
            $patchResponse = octaRequest('PATCH', $URL_CONTACTS . '/' . urlencode($idByEmail), $payload);
            log_step('Resposta PATCH após 409', $patchResponse);
            
            if ($patchResponse['http_code'] >= 200 && $patchResponse['http_code'] < 300) {
                log_step('Contato atualizado após conflito 409', ['id' => $idByEmail]);
                return $idByEmail;
            }
        }
    }
    
    log_step('ERRO: Falha ao criar/atualizar contato', ['response' => $response]);
    return null;
}

/**
 * Busca ID do contato por telefone local
 */
function findContactIdByPhoneLocal($localDigits) {
    global $URL_CONTACTS, $OCTADESK_API_KEY;
    
    if (empty($localDigits)) return null;
    
    log_step('Buscando contato por telefone local', ['localDigits' => $localDigits]);
    
    // Buscar por telefone local
    $response = octaRequest('GET', $URL_CONTACTS . '?phoneContact=' . urlencode($localDigits));
    
    log_step('Resposta busca por telefone', $response);
    
    if ($response['http_code'] == 200) {
        $jsonResponse = json_decode($response['body'], true);
        if (isset($jsonResponse[0]['id'])) {
            log_step('Contato encontrado por telefone', ['id' => $jsonResponse[0]['id']]);
            return $jsonResponse[0]['id'];
        }
    }
    
    log_step('Contato não encontrado por telefone');
    return null;
}

/**
 * Busca ID do contato por email
 */
function findContactIdByEmail($email) {
    global $URL_CONTACTS, $OCTADESK_API_KEY;
    
    if (empty($email)) return null;
    
    log_step('Buscando contato por email', ['email' => $email]);
    
    // Buscar por email
    $response = octaRequest('GET', $URL_CONTACTS . '?email=' . urlencode($email));
    
    log_step('Resposta busca por email', $response);
    
    if ($response['http_code'] == 200) {
        $jsonResponse = json_decode($response['body'], true);
        if (isset($jsonResponse[0]['id'])) {
            log_step('Contato encontrado por email', ['id' => $jsonResponse[0]['id']]);
            return $jsonResponse[0]['id'];
        }
    }
    
    log_step('Contato não encontrado por email');
    return null;
}

/**
 * Função para enviar mensagem via API send-template
 */
function sendTemplateMessage($payload) {
    global $URL_SEND_TPL, $OCTADESK_API_KEY;
    
    log_step('Enviando mensagem via send-template', ['url' => $URL_SEND_TPL]);
    
    $response = octaRequest('POST', $URL_SEND_TPL, $payload);
    
    log_step('Resposta send-template', $response);
    
    if ($response['http_code'] >= 200 && $response['http_code'] < 300) {
        return ['success' => true];
    } else {
        return [
            'success' => false, 
            'error' => 'HTTP ' . $response['http_code'] . ': ' . $response['body']
        ];
    }
}

/**
 * Função para fazer requisições para a API Octadesk
 */
function octaRequest($method, $url, $body = null) {
    global $OCTADESK_API_KEY;
    
    log_step('octaRequest', [
        'method' => $method,
        'url' => $url,
        'body' => $body
    ]);
    
    $headers = [
        'accept: application/json',
        'content-type: application/json',
        "X-API-KEY: {$OCTADESK_API_KEY}"
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => ($body !== null) ? json_encode($body) : null,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        log_step('cURL Error', ['error' => $error]);
        return ['http_code' => 0, 'body' => 'cURL Error: ' . $error];
    }
    
    log_step('Resposta API', [
        'http_code' => $httpCode,
        'body' => $response
    ]);
    
    return ['http_code' => $httpCode, 'body' => $response];
}
?>
