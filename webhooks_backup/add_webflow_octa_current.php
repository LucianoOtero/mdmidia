<?php
/**
 * add_webflow_octa.php — Webflow -> Octadesk (send-template)
 *
 * Upsert robusto de contato:
 * - Busca por telefone local (DDD+linha). Se não achar, busca por e-mail.
 * - Se POST der 409 por e-mail existente, busca por e-mail e faz PATCH.
 * - Contato guarda phone em formato local (10–11 dígitos, SEM +55).
 * - Mensagem usa E.164 (+55...).
 * - Custom fields (CPF/CEP/PLACA/VEICULO/ANO) via Person.customFields (array de objetos key/value).
 */
date_default_timezone_set('America/Sao_Paulo');
/* ================== CONFIG ================== */
$OCTADESK_API_KEY = 'b4e081fa-94ab-4456-8378-991bf995d3ea.d3e8e579-869d-4973-b34d-82391d08702b';
$API_BASE = 'https://o205242-d60.api004.octadesk.services';
$URL_SEND_TPL = $API_BASE . '/chat/conversation/send-template';
$URL_CONTACTS = $API_BASE . '/contacts';
$OCTADESK_FROM = '+551132301422';
$DEBUG_LOG_FILE = __DIR__ . '/octa_webflow_webhook.log';
$MAX_LOG_SIZE = 2 * 1024 * 1024;
$LOG_BACKUPS = 5;
/* ============================================ */
/* ================= LOGGING ================== */
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
  if (str_contains($kLow,'api') || str_contains($kLow,'token')) return '***MASKED***';
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
  if (str_contains($kLow,'gclid')) {
    return (strlen($v)>8) ? substr($v,0,4).'***'.substr($v,-4) : '***MASKED***';
  }
  return $v;
}
function log_step($title, $data = null) {
  global $DEBUG_LOG_FILE, $MAX_LOG_SIZE, $LOG_BACKUPS;
  try {
    log_rotate_if_needed($DEBUG_LOG_FILE, $MAX_LOG_SIZE, $LOG_BACKUPS);
    $ts = date('c');
    $line = "[$ts] $title";
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
  } catch (\Throwable $e) {}
}
/* ============================================ */
/* ============= HTTP helper Octadesk ============= */
function octa_request($method, $url, $body = null) {
  global $OCTADESK_API_KEY;
  $headers = [
    'accept: application/json',
    'content-type: application/json',
    "X-API-KEY: {$OCTADESK_API_KEY}"
  ];
  $bodyStr = ($body === null) ? '' : json_encode($body, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  log_step('OCTA_REQ', ['method'=>$method, 'url'=>$url, 'body'=>$body]);
  $ch = curl_init($url);
  $opts = [
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 25
  ];
  if ($method !== 'GET' && $bodyStr !== '') $opts[CURLOPT_POSTFIELDS] = $bodyStr;
  curl_setopt_array($ch, $opts);
  $resp = curl_exec($ch);
  $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err = curl_error($ch);
  curl_close($ch);
  log_step('OCTA_RES', ['http'=>$http, 'url'=>$url, 'err'=>$err, 'body_head'=>substr((string)$resp, 0, 600)]);
  $json = json_decode((string)$resp, true);
  return [$http, $json, $resp, $err];
}
/* ================================================ */
/* =================== HELPERS ==================== */
function onlyDigits($s){ return preg_replace('/\D+/','', (string)$s); }
/** Contacts: DDD+linha (10–11 dígitos), sem +55 */
function toLocalDigitsBR($ddd, $cel) {
  $n = onlyDigits((string)$ddd . (string)$cel);
  if (strpos($n, '55') === 0) $n = substr($n, 2); // remove DDI se vier
  return (strlen($n) >= 10 && strlen($n) <= 11) ? $n : null;
}
/** Chat: +55 + localDigits */
function toE164FromLocal($localDigits) {
  $n = onlyDigits($localDigits);
  if ($n === '') return null;
  if (strpos($n, '55') === 0) return '+'.$n;
  return '+55'.$n;
}
/** Custom fields pares (ignorando valores vazios) */
function buildCustomFieldsPairs($pairs) {
  $out = [];
  foreach ($pairs as $k => $v) if ($v !== null && $v !== '') $out[$k] = $v;
  return $out;
}
/* ================================================ */
/* =============== BODY / PARSE WEBFLOW =============== */
$raw = file_get_contents('php://input') ?: '';
log_step('Webhook recebido', ['raw_len'=>strlen($raw)]);
$data = json_decode($raw, true);
if (!is_array($data)) {
  log_step('JSON inválido', ['body_head'=>substr($raw,0,200)]);
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'Invalid JSON']);
  exit;
}
$src = $data['data'] ?? $data;
$nome = trim($src['NOME'] ?? '');
$dddCel = trim($src['DDD-CELULAR'] ?? '');
$celular = trim($src['CELULAR'] ?? '');
$produto = trim($src['produto'] ?? '');
$gclid = trim($src['GCLID_FLD'] ?? '');
$landing = $src['landing_url'] ?? '';
$utmSource = $src['utm_source'] ?? '';
$utmCampaign = $src['utm_campaign'] ?? '';
$email = trim(strtolower($src['Email'] ?? $src['EMAIL'] ?? $src['email'] ?? ''));
$cpf = trim($src['CPF'] ?? '');
$cep = trim($src['CEP'] ?? '');
$placa = trim($src['PLACA'] ?? '');
$veiculo = trim($src['VEICULO'] ?? $src['MARCA'] ?? '');
$anoDoVeiculo = trim($src['ANO'] ?? $src['ANO_MODELO'] ?? '');
$foneLocal = toLocalDigitsBR($dddCel, $celular);
$foneE164 = $foneLocal ? toE164FromLocal($foneLocal) : null;
log_step('Campos extraídos', [
  'nome'=>$nome,'foneLocal'=>$foneLocal,'foneE164'=>$foneE164,'produto'=>$produto,'gclid'=>$gclid,
  'utmSource'=>$utmSource,'utmCampaign'=>$utmCampaign,'landing'=>$landing,
  'email'=>$email,'cpf'=>$cpf,'cep'=>$cep,'placa'=>$placa,'veiculo'=>$veiculo,'ano_do_veiculo'=>$anoDoVeiculo
]);
if (!$foneLocal || !$foneE164) {
  log_step('Telefone inválido (formato local/E.164 ausente)');
  http_response_code(422);
  echo json_encode(['status'=>'error','message'=>'Telefone inválido']);
  exit;
}
/* ============ CUSTOM FIELDS (objeto) ============ */
// CORREÇÃO: Usaremos Person.customField = { chave: valor } (singular, conforme documentação)
$customObj = buildCustomFieldsPairs([
  'cpf' => $cpf,
  'cep' => $cep,
  'placa' => $placa,
  'veiculo' => $veiculo,
  'ano_do_veiculo' => $anoDoVeiculo,
]);
/* ============ BUSCAS AUXILIARES ============ */
function findContactIdByPhoneLocal($localDigits) {
  global $URL_CONTACTS;
  $qs = http_build_query([
    'filters' => [
      ['property' => 'phoneContacts.number', 'operator' => 'eq', 'value' => $localDigits]
    ],
    'limit' => 1, 'page' => 1
  ]);
  list($http, $json,) = octa_request('GET', $URL_CONTACTS . '?' . $qs, null);
  log_step('CONTACT_SEARCH_LOCAL', ['http'=>$http, 'qs'=>$qs, 'resp_head'=>substr(json_encode($json),0,300)]);
  if (is_array($json)) {
    if (!empty($json['data'][0]['id'])) return $json['data'][0]['id'];
    if (!empty($json['items'][0]['id'])) return $json['items'][0]['id'];
  }
  return null;
}
function findContactIdByEmail($email) {
  global $URL_CONTACTS;
  $email = trim(strtolower($email));
  if ($email === '') return null;
  $qs = http_build_query([
    'filters' => [
      ['property' => 'email', 'operator' => 'eq', 'value' => $email]
    ],
    'limit' => 1, 'page' => 1
  ]);
  list($http, $json,) = octa_request('GET', $URL_CONTACTS . '?' . $qs, null);
  log_step('CONTACT_SEARCH_EMAIL', ['http'=>$http, 'qs'=>$qs, 'resp_head'=>substr(json_encode($json),0,300)]);
  if (is_array($json)) {
    if (!empty($json['data'][0]['id'])) return $json['data'][0]['id'];
    if (!empty($json['items'][0]['id'])) return $json['items'][0]['id'];
  }
  return null;
}
/* ============ UPSERT CONTATO (com CF integrado) ============ */
// CORREÇÃO: Integramos customFields no upsert inicial para evitar chamada extra.
// Estrutura correta: customFields como array de objetos com key/value.
function upsertContactWithCF($localDigits, $nome, $email = null, $customObj = []) {
  global $URL_CONTACTS;
  $id = findContactIdByPhoneLocal($localDigits);
  if (!$id && $email) { $id = findContactIdByEmail($email); }
  
  // CORREÇÃO: Estrutura simplificada para evitar erro 500
  $payloadBase = [ 'name' => ($nome ?: 'Cliente') ];
  if ($email) $payloadBase['email'] = trim(strtolower($email));
  
  // CORREÇÃO: Usar 'customFields' (plural) com estrutura correta
  if (!empty($customObj)) {
    $customFieldsArray = [];
    foreach ($customObj as $key => $value) {
      $customFieldsArray[] = ['key' => $key, 'value' => $value];
    }
    $payloadBase['customFields'] = $customFieldsArray;
  }
  
  if ($id) {
    // CORREÇÃO: Estrutura simplificada para PATCH
    $payload = $payloadBase;
    if (!empty($customObj)) {
      $customFieldsArray = [];
      foreach ($customObj as $key => $value) {
        $customFieldsArray[] = ['key' => $key, 'value' => $value];
      }
      $payload['customFields'] = $customFieldsArray;
    }
    
    log_step('CONTACT_UPDATE_REQ', ['id'=>$id, 'payload'=>$payload]);
    list($httpU, , $rawU) = octa_request('PATCH', $URL_CONTACTS . '/' . urlencode($id), $payload);
    if ($httpU >= 200 && $httpU < 300) {
      log_step('CONTACT_UPDATE_OK', ['id'=>$id]);
      return $id;
    }
    log_step('CONTACT_UPDATE_FAIL', ['http'=>$httpU, 'raw_head'=>substr((string)$rawU,0,300)]);
    return $id;
  }
  
  // POST criar - CORREÇÃO: Estrutura simplificada
  $payloadCreate = $payloadBase;
  if (!empty($customObj)) {
    $customFieldsArray = [];
    foreach ($customObj as $key => $value) {
      $customFieldsArray[] = ['key' => $key, 'value' => $value];
    }
    $payloadCreate['customFields'] = $customFieldsArray;
  }
  
  // CORREÇÃO: Adicionar phoneContacts apenas se necessário
  if ($localDigits) {
    $payloadCreate['phoneContacts'] = [
      [
        'number' => $localDigits,
        'countryCode' => '55',
        'type' => 1  // 1 = Cell (Celular)
      ]
    ];
  }
  
  log_step('CONTACT_CREATE_REQ', ['payload'=>$payloadCreate]);
  list($httpC, $jsonC, $rawC) = octa_request('POST', $URL_CONTACTS, $payloadCreate);
  $newId = is_array($jsonC) ? ($jsonC['id'] ?? $jsonC['_id'] ?? null) : null;
  
  if ($httpC >= 200 && $httpC < 300 && $newId) {
    log_step('CONTACT_CREATE_OK', ['id'=>$newId]);
    
    // CORREÇÃO: Se customFields não foram salvos, tentar PATCH separado
    if (!empty($customObj)) {
      $customFieldsArray = [];
      foreach ($customObj as $key => $value) {
        $customFieldsArray[] = ['key' => $key, 'value' => $value];
      }
      $patchPayload = ['customFields' => $customFieldsArray];
      log_step('CONTACT_PATCH_CF_AFTER_CREATE_REQ', ['id'=>$newId, 'payload'=>$patchPayload]);
      list($httpP, , $rawP) = octa_request('PATCH', $URL_CONTACTS . '/' . urlencode($newId), $patchPayload);
      if ($httpP >= 200 && $httpP < 300) {
        log_step('CONTACT_PATCH_CF_AFTER_CREATE_OK', ['id'=>$newId]);
      } else {
        log_step('CONTACT_PATCH_CF_AFTER_CREATE_FAIL', ['http'=>$httpP, 'raw_head'=>substr((string)$rawP,0,300)]);
      }
    }
    
    return $newId;
  }
  
  // 409 por e-mail -> buscar por e-mail e PATCH
  if ($httpC == 409 && $email) {
    log_step('CONTACT_CREATE_CONFLICT', ['raw_head'=>substr((string)$rawC,0,300)]);
    $idByEmail = findContactIdByEmail($email);
    if ($idByEmail) {
      $payload = $payloadBase;
      if (!empty($customObj)) {
        $customFieldsArray = [];
        foreach ($customObj as $key => $value) {
          $customFieldsArray[] = ['key' => $key, 'value' => $value];
        }
        $payload['customFields'] = $customFieldsArray;
      }
      
      log_step('CONTACT_PATCH_AFTER_409_REQ', ['id'=>$idByEmail, 'payload'=>$payload]);
      list($httpP, , $rawP) = octa_request('PATCH', $URL_CONTACTS . '/' . urlencode($idByEmail), $payload);
      if ($httpP >= 200 && $httpP < 300) {
        log_step('CONTACT_PATCH_AFTER_409_OK', ['id'=>$idByEmail]);
        return $idByEmail;
      }
      log_step('CONTACT_PATCH_AFTER_409_FAIL', ['http'=>$httpP, 'raw_head'=>substr((string)$rawP,0,300)]);
      return $idByEmail;
    }
  }
  
  log_step('CONTACT_CREATE_FAIL', ['http'=>$httpC, 'raw_head'=>substr((string)$rawC,0,400)]);
  return null;
}
/* ============ PATCH CUSTOM FIELDS (se necessário, mas integrado acima) ============ */
// Função mantida para compatibilidade, mas agora customField são enviados no upsert.
function patchCustomFieldObject($contactId, $nome, $email, $customObj) {
  global $URL_CONTACTS;
  if (!$contactId || empty($customObj)) return false;
  
  $payload = [
    'name' => ($nome ?: 'Cliente'),
    'email' => ($email ? trim(strtolower($email)) : null),
    'customFields' => array_map(function($key, $value) {
      return ['key' => $key, 'value' => $value];
    }, array_keys($customObj), array_values($customObj))
  ];
  
  log_step('CONTACT_PATCH_CF_OBJ_REQ', ['id'=>$contactId, 'payload'=>$payload]);
  list($http, , $raw) = octa_request('PATCH', $URL_CONTACTS . '/' . urlencode($contactId), $payload);
  
  if ($http >= 200 && $http < 300) {
    log_step('CONTACT_PATCH_CF_OBJ_OK', ['id'=>$contactId, 'keys'=>array_keys($customObj)]);
    return true;
  }
  
  log_step('CONTACT_PATCH_CF_OBJ_FAIL', ['http'=>$http, 'raw_head'=>substr((string)$raw,0,400)]);
  return false;
}
/* ====== EXECUÇÃO: contato com CF -> template ====== */
$contactId = upsertContactWithCF($foneLocal, $nome, $email, $customObj);

if ($contactId) {
  // Se necessário, PATCH extra (mas já integrado)
  $okCF = true; // Como integrado, assumimos OK se upsert succeeded
  log_step('CONTACT_CF_STATUS', ['contactId'=>$contactId, 'okCF'=>$okCF, 'keys'=>array_keys($customObj)]);
} else {
  log_step('CONTACT_UPSERT_FAIL', ['foneLocal'=>$foneLocal]);
}

/* ==== Payload do send-template (usa E.164) ==== */
$payloadSend = [
  'target' => [
    'contact' => [
      'name' => ($nome !== '' ? $nome : 'Cliente'),
      'email' => ($email ?: null),
      'phoneContact' => ['number' => $foneE164],
    ],
    'customFields' => [
      ['key' => 'nome-contato', 'value' => $nome ?: ''],
      ['key' => 'gclid', 'value' => $gclid ?: '']
    ],
    'tags' => array_values(array_filter(['lead-webflow', $produto ? "produto:$produto" : null]))
  ],
  'content' => [
    'templateMessage' => [
      'code' => 'site_cotacao',
      'language' => 'pt_BR',
      'components' => [[
        'type' => 'body',
        'parameters' => [[ 'type' => 'text', 'text' => ($nome !== '' ? $nome : 'cliente') ]]
      ]]
    ]
  ],
  'origin' => ['from' => ['number' => $OCTADESK_FROM]],
  'options' => ['automaticAssign' => true],
  'metadata'=> [
    'campaign' => 'webflow_form',
    'utm_source' => $utmSource,
    'utm_campaign' => $utmCampaign,
    'landing_url' => $landing
  ]
];

log_step('Payload Send-Template (mascarado)', $payloadSend);

/* ==== Envia template ==== */
list($httpTpl, $jsonTpl, $rawTpl) = octa_request('POST', $URL_SEND_TPL, $payloadSend);
$conversationId = is_array($jsonTpl) ? ($jsonTpl['conversationId'] ?? ($jsonTpl['result']['roomKey'] ?? null)) : null;

/* ==== Resposta final ==== */
header('Content-Type: application/json; charset=utf-8');
if ($httpTpl >= 200 && $httpTpl < 300) {
  log_step('Fluxo OK', ['conversationId'=>$conversationId, 'contactId'=>$contactId]);
  echo json_encode([
    'status' => 'success',
    'octadesk_http' => $httpTpl,
    'conversationId' => $conversationId,
    'contactId' => $contactId
  ]);
} else {
  log_step('Fluxo com erro', ['http'=>$httpTpl, 'conversationId'=>$conversationId, 'contactId'=>$contactId]);
  http_response_code(207);
  echo json_encode([
    'status' => 'partial',
    'octadesk_http' => $httpTpl,
    'body' => $rawTpl,
    'contactId' => $contactId
  ]);
}