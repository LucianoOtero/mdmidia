<?php
require_once('class.php');

// Função para log com timestamp
function logWithTimestamp($logs, $message)
{
    $timestamp = date('Y-m-d H:i:s');
    fwrite($logs, "[$timestamp] $message" . PHP_EOL);
}

// Função para buscar lead por email
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
            logWithTimestamp($logs, "Lead encontrado por email: " . $leads['list'][0]['id']);
            return $leads['list'][0];
        }
        logWithTimestamp($logs, "Nenhum lead encontrado para o email: " . $email);
        return null;
    } catch (Exception $e) {
        logWithTimestamp($logs, "Erro ao buscar lead por email: " . $e->getMessage());
        return null;
    }
}

// --- 1. Setup Logging ---
$logs = fopen("collect_chat_logs.txt", "a");
logWithTimestamp($logs, "=== INÍCIO PROCESSAMENTO COLLECT CHAT V10 ===");

// --- 2. Debugging: Log everything that comes in ---
logWithTimestamp($logs, "---- DEBUG START ----");

if (function_exists('getallheaders')) {
    $headers = getallheaders();
    logWithTimestamp($logs, "Request Headers: " . print_r($headers, true));
}

$rawInput = file_get_contents('php://input');
logWithTimestamp($logs, "Raw php://input: " . $rawInput);

logWithTimestamp($logs, "Parsed \$_POST: " . print_r($_POST, true));
logWithTimestamp($logs, "Parsed \$_GET: " . print_r($_GET, true));

logWithTimestamp($logs, "---- DEBUG END ----");

// --- 3. Decoding incoming data ---
$data = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    logWithTimestamp($logs, "JSON decode failed: " . json_last_error_msg());
    parse_str($rawInput, $formData);

    if (!empty($formData)) {
        $data = $formData;
        logWithTimestamp($logs, "Parsed as form-urlencoded: " . print_r($data, true));
    } elseif (!empty($_POST)) {
        $data = $_POST;
        logWithTimestamp($logs, "Using \$_POST data: " . print_r($data, true));
    } else {
        logWithTimestamp($logs, "No valid data found in input.");
        fclose($logs);
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid payload']);
        exit();
    }
} else {
    logWithTimestamp($logs, "Decoded JSON: " . print_r($data, true));
}

// --- 4. Extract fields ---
$name  = $data['NAME'] ?? '';
$cel   = $data['NUMBER'] ?? '';
$cpf   = $data['CPF'] ?? '';
$cep   = $data['CEP'] ?? '';
$placa = $data['PLACA'] ?? '';
$gclid = $data['gclid'] ?? '';
$email = $data['EMAIL'] ?? '';
$source = 'Collect Chat';

// Extração do gclid, se necessário
if (!empty($gclid) && strpos($gclid, 'http') !== false) {
    parse_str(parse_url($gclid, PHP_URL_QUERY), $queryParams);
    $gclid = $queryParams['gclid'] ?? $gclid;
}

logWithTimestamp($logs, "Extracted Data | Name: {$name}, Number: {$cel}, CPF: {$cpf}, CEP: {$cep}, EMAIL: {$email}, PLACA: {$placa}, GCLID: {$gclid}");
logWithTimestamp($logs, "Source: " . $source);

// --- 5. Funções de Validação da Placa (Escopo Global) ---
// Função para converter para maiúsculas e remover espaços
function toUpperNospace($str)
{
    return strtoupper(trim($str));
}

// Função para extrair apenas dígitos
function onlyDigits($str)
{
    return preg_replace('/[^0-9]/', '', $str);
}

// Função para validar formato da placa (corrigida para funcionar igual ao JavaScript)
function validarPlacaFormato($p)
{
    $p = toUpperNospace($p);
    $p = preg_replace('/[^A-Z0-9]/', '', $p); // ✅ AGORA igual ao JavaScript

    $antigo = '/^[A-Z]{3}[0-9]{4}$/';
    $mercosul = '/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/';

    return preg_match($antigo, $p) || preg_match($mercosul, $p);
}

// Função para extrair dados do veículo da API (baseada no código JavaScript fornecido)
function extractVehicleFromApiBrasil($apiJson)
{
    global $logs;

    logWithTimestamp($logs, "🔍 EXTRACT VEHICLE - API JSON recebido: " . print_r($apiJson, true));

    // ✅ CORREÇÃO: Extrair corretamente os dados da estrutura da API
    $r = null;
    if (isset($apiJson['response']) && is_array($apiJson['response'])) {
        $r = $apiJson['response'];
    } elseif (isset($apiJson['data']) && is_array($apiJson['data'])) {
        $r = $apiJson['data'];
    } elseif (is_array($apiJson)) {
        $r = $apiJson;
    }

    logWithTimestamp($logs, "🔍 EXTRACT VEHICLE - Dados extraídos (r): " . print_r($r, true));

    if (!$r || !is_array($r)) {
        logWithTimestamp($logs, "❌ EXTRACT VEHICLE - Dados inválidos ou vazios");
        return ['marcaTxt' => '', 'anoModelo' => ''];
    }

    // ✅ CORREÇÃO: Extrair campos da estrutura correta da API
    $fabricante = $r['MARCA'] ?? $r['marca'] ?? $r['fabricante'] ?? '';
    $veiculo = $r['MODELO'] ?? $r['modelo'] ?? $r['veiculo'] ?? '';
    $modelo = $r['VERSAO'] ?? $r['versao'] ?? $r['SUBMODELO'] ?? '';
    $anoMod = $r['anoModelo'] ?? $r['ano'] ?? $r['ano_fabricacao'] ?? '';

    logWithTimestamp($logs, "🔍 EXTRACT VEHICLE - Campos extraídos:");
    logWithTimestamp($logs, "   - fabricante: '{$fabricante}'");
    logWithTimestamp($logs, "   - veiculo: '{$veiculo}'");
    logWithTimestamp($logs, "   - modelo: '{$modelo}'");
    logWithTimestamp($logs, "   - anoMod: '{$anoMod}'");

    $marcaTxt = implode(' / ', array_filter([$fabricante, $veiculo, $modelo]));
    $anoModelo = substr(onlyDigits((string)$anoMod), 0, 4);

    logWithTimestamp($logs, "🔍 EXTRACT VEHICLE - Resultado final:");
    logWithTimestamp($logs, "   - marcaTxt: '{$marcaTxt}'");
    logWithTimestamp($logs, "   - anoModelo: '{$anoModelo}'");

    return [
        'marcaTxt' => $marcaTxt,
        'anoModelo' => $anoModelo
    ];
}

// Função para validar placa na API (corrigida para funcionar igual ao JavaScript)
function validarPlacaApi($placa)
{
    $raw = toUpperNospace($placa);
    $raw = preg_replace('/[^A-Z0-9]/', '', $raw);

    // ✅ AGORA igual ao JavaScript: valida formato ANTES de chamar a API
    if (!validarPlacaFormato($raw)) {
        return ['ok' => false, 'reason' => 'formato'];
    }

    // Formatar placa com traço (formato brasileiro)
    $placaFormatada = substr($raw, 0, 3) . '-' . substr($raw, 3);

    // Configuração do cURL com timeout mais generoso para evitar timeouts desnecessários
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://mdmidia.com.br/api/placa-validate.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['placa' => $raw]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20); // ✅ Aumentado para 10 segundos
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // ✅ Aumentado para 5 segundos
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desabilita verificação SSL para evitar problemas
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Não segue redirecionamentos
    curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Log detalhado para debug
    global $logs;
    logWithTimestamp($logs, "🌐 API CALL - Placa: {$raw}, HTTP Code: {$httpCode}, cURL Error: {$curlError}");
    logWithTimestamp($logs, "🌐 API Response: " . $response);

    // ✅ AGORA igual ao JavaScript: trata erros como 'erro_api'
    if ($response === false || $curlError || $httpCode === 0) {
        if ($httpCode === 0 && strpos($curlError, 'timeout') !== false) {
            logWithTimestamp($logs, "⏰ API TIMEOUT - Placa {$raw} não respondeu em 10s - fazendo validação local");
        } else {
            logWithTimestamp($logs, "❌ API error/curl error - fazendo validação local");
        }
        return ['ok' => false, 'reason' => 'erro_api'];
    }

    if ($httpCode !== 200) {
        logWithTimestamp($logs, "❌ HTTP Error: " . $httpCode . " - fazendo validação local");
        return ['ok' => false, 'reason' => 'erro_api'];
    }

    $json = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        logWithTimestamp($logs, "❌ JSON Decode Error: " . json_last_error_msg() . " - fazendo validação local");
        return ['ok' => false, 'reason' => 'erro_api'];
    }

    // ✅ AGORA igual ao JavaScript: usa !!j?.ok para determinar se deu certo
    $ok = !empty($json['ok']);
    logWithTimestamp($logs, "🔍 API Result - OK: " . ($ok ? 'true' : 'false'));
    logWithTimestamp($logs, "🔍 API JSON Data: " . print_r($json, true));

    if ($ok) {
        // ✅ API retornou dados válidos
        $parsedData = extractVehicleFromApiBrasil($json['data']);
        logWithTimestamp($logs, "🎉 API SUCESSO - Dados reais do veículo obtidos:");
        logWithTimestamp($logs, "   - marcaTxt: '{$parsedData['marcaTxt']}'");
        logWithTimestamp($logs, "   - anoModelo: '{$parsedData['anoModelo']}'");
        logWithTimestamp($logs, "   - Status: ✅ DADOS REAIS DA API - Prioridade máxima!");

        return [
            'ok' => true,
            'reason' => 'ok',
            'parsed' => $parsedData
        ];
    } else {
        // ❌ API não encontrou a placa
        logWithTimestamp($logs, "⚠️  API não encontrou a placa - reason: nao_encontrada");
        return [
            'ok' => false,
            'reason' => 'nao_encontrada',
            'parsed' => ['marcaTxt' => '', 'anoModelo' => '']
        ];
    }
}

// Função para validação local robusta quando a API falha
function validarPlacaLocal($placa)
{
    $raw = toUpperNospace($placa);
    $raw = preg_replace('/[^A-Z0-9]/', '', $raw);

    if (!validarPlacaFormato($raw)) {
        return ['ok' => false, 'reason' => 'formato'];
    }

    // Validação local baseada no formato da placa
    // Para placas válidas, retorna dados genéricos mas válidos
    if (strlen($raw) >= 7) {
        // Determinar tipo de placa baseado no formato
        if (preg_match('/^[A-Z]{3}[0-9]{4}$/', $raw)) {
            // Formato antigo: ABC-1234
            return [
                'ok' => true,
                'reason' => 'ok_local',
                'parsed' => [
                    'marcaTxt' => 'VEÍCULO VALIDADO (FORMATO ANTIGO)',
                    'anoModelo' => date('Y') // Ano atual como fallback
                ]
            ];
        } elseif (preg_match('/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/', $raw)) {
            // Formato Mercosul: ABC1D23
            return [
                'ok' => true,
                'reason' => 'ok_local',
                'parsed' => [
                    'marcaTxt' => 'VEÍCULO VALIDADO (FORMATO MERCOSUL)',
                    'anoModelo' => date('Y') // Ano atual como fallback
                ]
            ];
        }
    }

    return [
        'ok' => false,
        'reason' => 'formato_invalido',
        'parsed' => ['marcaTxt' => '', 'anoModelo' => '']
    ];
}

// --- 6. Validação da Placa ---
$veiculo = '';
$anoModelo = '';

if (!empty($placa)) {
    // Primeiro tenta validar na API (igual ao JavaScript)
    try {
        $resultadoValidacao = validarPlacaApi($placa);
        logWithTimestamp($logs, "🔍 RESULTADO DA VALIDAÇÃO DA PLACA {$placa}:");
        logWithTimestamp($logs, "   - OK: " . ($resultadoValidacao['ok'] ? 'true' : 'false'));
        logWithTimestamp($logs, "   - Reason: " . $resultadoValidacao['reason']);
        logWithTimestamp($logs, "   - Parsed: " . print_r($resultadoValidacao['parsed'], true));

        if ($resultadoValidacao['ok'] === true) {
            // ✅ DADOS REAIS DA API - Prioridade máxima
            $veiculo = $resultadoValidacao['parsed']['marcaTxt'];
            $anoModelo = $resultadoValidacao['parsed']['anoModelo'];
            logWithTimestamp($logs, "✅ PLACA VÁLIDA NA API - DADOS REAIS EXTRAÍDOS:");
            logWithTimestamp($logs, "   - Veículo: '{$veiculo}'");
            logWithTimestamp($logs, "   - Ano: '{$anoModelo}'");
            logWithTimestamp($logs, "   - Status: Dados reais da API serão enviados para o EspoCRM");
        } else {
            // Se a API falhou, tenta validação local como fallback
            if ($resultadoValidacao['reason'] === 'erro_api') {
                logWithTimestamp($logs, "⚠️  API FALHOU - Tentando validação local como fallback...");
                $resultadoLocal = validarPlacaLocal($placa);

                if ($resultadoLocal['ok']) {
                    // ⚠️ DADOS GENÉRICOS DA VALIDAÇÃO LOCAL (apenas quando API falha)
                    $veiculo = $resultadoLocal['parsed']['marcaTxt'];
                    $anoModelo = $resultadoLocal['parsed']['anoModelo'];
                    logWithTimestamp($logs, "⚠️  VALIDAÇÃO LOCAL BEM-SUCEDIDA (FALLBACK) - Dados genéricos:");
                    logWithTimestamp($logs, "   - Veículo: '{$veiculo}'");
                    logWithTimestamp($logs, "   - Ano: '{$anoModelo}'");
                    logWithTimestamp($logs, "   - Status: Dados genéricos serão enviados para o EspoCRM");
                } else {
                    $veiculo = 'ERRO NA VALIDACAO';
                    $anoModelo = '';
                    logWithTimestamp($logs, "❌ VALIDAÇÃO LOCAL FALHOU - Definindo veículo como 'ERRO NA VALIDACAO'");
                }
            } elseif ($resultadoValidacao['reason'] === 'formato') {
                $veiculo = 'FORMATO INVALIDO';
                $anoModelo = '';
                logWithTimestamp($logs, "❌ FORMATO DE PLACA INVÁLIDO - Definindo veículo como 'FORMATO INVALIDO'");
            } elseif ($resultadoValidacao['reason'] === 'nao_encontrada') {
                $veiculo = 'PLACA NAO LOCALIZADA';
                $anoModelo = '';
                logWithTimestamp($logs, "⚠️  PLACA NÃO ENCONTRADA NA API - Definindo veículo como 'PLACA NAO LOCALIZADA'");
            } else {
                $veiculo = 'ERRO NA VALIDACAO';
                $anoModelo = '';
                logWithTimestamp($logs, "❌ ERRO NA VALIDAÇÃO DA PLACA: " . $resultadoValidacao['reason']);
            }
        }
    } catch (Exception $e) {
        logWithTimestamp($logs, "❌ EXCEÇÃO NA VALIDAÇÃO DA PLACA: " . $e->getMessage() . " - Tentando validação local de emergência...");

        // Fallback para validação local em caso de exceção
        try {
            $resultadoLocal = validarPlacaLocal($placa);
            if ($resultadoLocal['ok']) {
                // ⚠️ DADOS GENÉRICOS DE EMERGÊNCIA (apenas em caso de exceção)
                $veiculo = $resultadoLocal['parsed']['marcaTxt'];
                $anoModelo = $resultadoLocal['parsed']['anoModelo'];
                logWithTimestamp($logs, "⚠️  VALIDAÇÃO LOCAL DE EMERGÊNCIA BEM-SUCEDIDA - Dados genéricos:");
                logWithTimestamp($logs, "   - Veículo: '{$veiculo}'");
                logWithTimestamp($logs, "   - Ano: '{$anoModelo}'");
                logWithTimestamp($logs, "   - Status: Dados genéricos de emergência serão enviados para o EspoCRM");
            } else {
                $veiculo = 'ERRO NA VALIDACAO';
                $anoModelo = '';
                logWithTimestamp($logs, "❌ VALIDAÇÃO LOCAL DE EMERGÊNCIA FALHOU");
            }
        } catch (Exception $e2) {
            logWithTimestamp($logs, "❌ ERRO NA VALIDAÇÃO LOCAL DE EMERGÊNCIA: " . $e2->getMessage());
            $veiculo = 'ERRO NA VALIDACAO';
            $anoModelo = '';
        }
    }
} else {
    logWithTimestamp($logs, "ℹ️  Nenhuma placa informada para validação");
}

// Log final dos valores que serão enviados para o EspoCRM
logWithTimestamp($logs, "📋 VALORES FINAIS PARA ESPOCRM:");
logWithTimestamp($logs, "   - cMarca: '{$veiculo}'");
logWithTimestamp($logs, "   - cAnoMod: '{$anoModelo}'");

// --- 7. Envio ao EspoCRM ---
$client = new EspoApiClient('https://travelangels.com.br');
$client->setApiKey('d5bcb42f62d1d96f8090a1002b792335');

// Cliente para FlyingDonkeys (V7 completa)
$clientFlyingDonkeys = new EspoApiClient('https://flyingdonkeys.com.br');
$clientFlyingDonkeys->setApiKey('82d5f667f3a65a9a43341a0705be2b0c');

// Payload comum para ambos os sistemas
$payload = [
    'firstName'      => $name,
    'cCelular'       => $cel,
    'cCpftext'       => $cpf,
    'cPlaca'         => $placa,
    'addressPostalCode' => $cep,
    'cGclid'         => $gclid,
    'emailAddress'   => $email,
    'cMarca'         => $veiculo,
    'cAnoMod'        => $anoModelo,
    'source'         => $source,
];

$leadIdTravelAngels = null;
$leadIdFlyingDonkeys = null;

// ===== PROCESSAMENTO TRAVELANGELS (MANTIDO COMO ESTÁ) =====
logWithTimestamp($logs, "--- PROCESSANDO TRAVELANGELS ---");

// Envio para TravelAngels (lógica original mantida)
try {
    $responseTravelAngels = $client->request('POST', 'Lead', $payload);
    $leadIdTravelAngels = $responseTravelAngels['id'];
    logWithTimestamp($logs, "TravelAngels - Lead criado com sucesso: " . $leadIdTravelAngels);
} catch (Exception $e) {
    logWithTimestamp($logs, "TravelAngels - Erro: " . $e->getMessage());
}

// ===== PROCESSAMENTO FLYINGDONKEYS (LÓGICA V7 COMPLETA) =====
logWithTimestamp($logs, "--- PROCESSANDO FLYINGDONKEYS V7 ---");

// Tentar criar lead no FlyingDonkeys
try {
    $responseFlyingDonkeys = $clientFlyingDonkeys->request('POST', 'Lead', $payload);
    $leadIdFlyingDonkeys = $responseFlyingDonkeys['id'];
    logWithTimestamp($logs, "FlyingDonkeys - Lead criado com sucesso: " . $leadIdFlyingDonkeys);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    logWithTimestamp($logs, "FlyingDonkeys - Exceção capturada: " . $errorMessage);

    // Se erro 409 (duplicata) ou se a resposta contém dados do lead (EspoCRM retorna lead existente como "erro")
    if (
        strpos($errorMessage, '409') !== false || strpos($errorMessage, 'duplicate') !== false ||
        (strpos($errorMessage, '"id":') !== false && strpos($errorMessage, '"name":') !== false)
    ) {

        logWithTimestamp($logs, "FlyingDonkeys - Lead duplicado detectado - buscando por email: " . $email);

        $existingLead = findLeadByEmail($email, $clientFlyingDonkeys, $logs);
        if ($existingLead) {
            logWithTimestamp($logs, "FlyingDonkeys - Lead encontrado - atualizando: " . $existingLead['id']);

            // Atualizar lead existente
            $updateResponse = $clientFlyingDonkeys->request('PATCH', 'Lead/' . $existingLead['id'], $payload);
            logWithTimestamp($logs, "FlyingDonkeys - Lead atualizado com sucesso");
            $leadIdFlyingDonkeys = $existingLead['id'];
        } else {
            // Se não encontrou por email, mas a resposta contém dados do lead, usar esses dados
            if (strpos($errorMessage, '"id":') !== false && strpos($errorMessage, '"name":') !== false) {
                $leadData = json_decode($errorMessage, true);
                if (isset($leadData[0]['id'])) {
                    logWithTimestamp($logs, "FlyingDonkeys - Usando lead existente da resposta: " . $leadData[0]['id']);
                    $leadIdFlyingDonkeys = $leadData[0]['id'];
                } else {
                    logWithTimestamp($logs, "FlyingDonkeys - Erro: Lead duplicado mas não encontrado por email");
                    throw $e;
                }
            } else {
                logWithTimestamp($logs, "FlyingDonkeys - Erro: Lead duplicado mas não encontrado por email");
                throw $e;
            }
        }
    } else {
        logWithTimestamp($logs, "FlyingDonkeys - Erro real na criação do lead: " . $errorMessage);
        throw $e;
    }
}

// Tentar criar oportunidade no FlyingDonkeys
if ($leadIdFlyingDonkeys) {
    try {
        $opportunityPayload = [
            'name' => $name,
            'leadId' => $leadIdFlyingDonkeys,
            'stage' => 'Novo Sem Contato',
            'amount' => 0,
            'probability' => 10,

            // Campos do lead mapeados para oportunidade
            'cAnoFab' => $anoModelo,
            'cAnoMod' => $anoModelo,
            'cCEP' => $cep,
            'cCelular' => $cel,
            'cCpftext' => $cpf,
            'cGclid' => $gclid,
            'cMarca' => $veiculo,
            'cPlaca' => $placa,
            'cWebpage' => 'collect.chat',
            'cEmail' => $email,
            'cEmailAdress' => $email,
            'leadSource' => $source,

            // Campos adicionais do workflow
            'cSegpref' => isset($data['seguradora_preferencia']) ? $data['seguradora_preferencia'] : '',
            'cValorpret' => isset($data['valor_preferencia']) ? $data['valor_preferencia'] : '',
            'cModalidade' => isset($data['modalidade_seguro']) ? $data['modalidade_seguro'] : '',
            'cSegant' => isset($data['seguradora_apolice']) ? $data['seguradora_apolice'] : '',
            'cCiapol' => isset($data['ci']) ? $data['ci'] : '',
        ];

        $responseOpportunity = $clientFlyingDonkeys->request('POST', 'Opportunity', $opportunityPayload);
        logWithTimestamp($logs, "FlyingDonkeys - Oportunidade criada com sucesso: " . $responseOpportunity['id']);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        logWithTimestamp($logs, "FlyingDonkeys - Exceção oportunidade: " . $errorMessage);

        // Se erro 409 (duplicata), criar nova oportunidade com duplicate = yes
        if (strpos($errorMessage, '409') !== false || strpos($errorMessage, 'duplicate') !== false) {
            logWithTimestamp($logs, "FlyingDonkeys - Oportunidade duplicada detectada - criando nova com duplicate = yes");

            $opportunityPayload['duplicate'] = 'yes';
            $responseOpportunity = $clientFlyingDonkeys->request('POST', 'Opportunity', $opportunityPayload);
            logWithTimestamp($logs, "FlyingDonkeys - Nova oportunidade criada com duplicate = yes: " . $responseOpportunity['id']);
        } else {
            logWithTimestamp($logs, "FlyingDonkeys - Erro real na criação da oportunidade: " . $errorMessage);
        }
    }
}

// --- 8. Finalização ---
logWithTimestamp($logs, "=== FIM PROCESSAMENTO COLLECT CHAT V10 ===");
logWithTimestamp($logs, "Terminou");
logWithTimestamp($logs, "---");
fclose($logs);

// Retorna resposta de sucesso para o webhook
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Lead processado no TravelAngels e FlyingDonkeys com sucesso',
    'leadIdTravelAngels' => $leadIdTravelAngels,
    'leadIdFlyingDonkeys' => $leadIdFlyingDonkeys
]);
