<?php
require_once('class.php');

// --- 1. Setup Logging ---
$logs = fopen("collect_chat_logs.txt", "a");
fwrite($logs, "---" . date('Y-m-d H:i:s') . "---" . PHP_EOL);

// --- 2. Debugging: Log everything that comes in ---
fwrite($logs, "---- DEBUG START ----" . PHP_EOL);

if (function_exists('getallheaders')) {
    $headers = getallheaders();
    fwrite($logs, "Request Headers: " . print_r($headers, true) . PHP_EOL);
}

$rawInput = file_get_contents('php://input');
fwrite($logs, "Raw php://input: " . $rawInput . PHP_EOL);

fwrite($logs, "Parsed \$_POST: " . print_r($_POST, true) . PHP_EOL);
fwrite($logs, "Parsed \$_GET: " . print_r($_GET, true) . PHP_EOL);

fwrite($logs, "---- DEBUG END ----" . PHP_EOL);

// --- 3. Decoding incoming data ---
$data = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    fwrite($logs, "JSON decode failed: " . json_last_error_msg() . PHP_EOL);
    parse_str($rawInput, $formData);

    if (!empty($formData)) {
        $data = $formData;
        fwrite($logs, "Parsed as form-urlencoded: " . print_r($data, true) . PHP_EOL);
    } elseif (!empty($_POST)) {
        $data = $_POST;
        fwrite($logs, "Using \$_POST data: " . print_r($data, true) . PHP_EOL);
    } else {
        fwrite($logs, "No valid data found in input." . PHP_EOL);
        fclose($logs);
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid payload']);
        exit();
    }
} else {
    fwrite($logs, "Decoded JSON: " . print_r($data, true) . PHP_EOL);
}

// --- 4. Extract fields ---
$name  = $data['NAME'] ?? '';
$cel   = $data['NUMBER'] ?? '';
$cpf   = $data['CPF'] ?? '';
$cep   = $data['CEP'] ?? '';
$placa = $data['PLACA'] ?? '';
$gclid = $data['gclid'] ?? '';
$email = $data['EMAIL'] ?? '';

// Extração do gclid, se necessário
if (!empty($gclid) && strpos($gclid, 'http') !== false) {
    parse_str(parse_url($gclid, PHP_URL_QUERY), $queryParams);
    $gclid = $queryParams['gclid'] ?? $gclid;
}

fwrite($logs, "Extracted Data | Name: {$name}, Number: {$cel}, CPF: {$cpf}, CEP: {$cep}, EMAIL: {$email}, PLACA: {$placa}, GCLID: {$gclid}" . PHP_EOL);

// --- 5. Funções de Validação da Placa (Escopo Global) ---
// Função para converter para maiúsculas e remover espaços
function toUpperNospace($str) {
    return strtoupper(trim($str));
}

// Função para extrair apenas dígitos
function onlyDigits($str) {
    return preg_replace('/[^0-9]/', '', $str);
}

// Função para validar formato da placa (corrigida para funcionar igual ao JavaScript)
function validarPlacaFormato($p) {
    $p = toUpperNospace($p);
    $p = preg_replace('/[^A-Z0-9]/', '', $p); // ✅ AGORA igual ao JavaScript
    
    $antigo = '/^[A-Z]{3}[0-9]{4}$/';
    $mercosul = '/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/';
    
    return preg_match($antigo, $p) || preg_match($mercosul, $p);
}

// Função para extrair dados do veículo da API (baseada no código JavaScript fornecido)
function extractVehicleFromApiBrasil($apiJson) {
    global $logs;
    
    fwrite($logs, "🔍 EXTRACT VEHICLE - API JSON recebido: " . print_r($apiJson, true) . PHP_EOL);
    
    // ✅ CORREÇÃO: Extrair corretamente os dados da estrutura da API
    $r = null;
    if (isset($apiJson['response']) && is_array($apiJson['response'])) {
        $r = $apiJson['response'];
    } elseif (isset($apiJson['data']) && is_array($apiJson['data'])) {
        $r = $apiJson['data'];
    } elseif (is_array($apiJson)) {
        $r = $apiJson;
    }
    
    fwrite($logs, "🔍 EXTRACT VEHICLE - Dados extraídos (r): " . print_r($r, true) . PHP_EOL);
    
    if (!$r || !is_array($r)) {
        fwrite($logs, "❌ EXTRACT VEHICLE - Dados inválidos ou vazios" . PHP_EOL);
        return ['marcaTxt' => '', 'anoModelo' => ''];
    }
    
    // ✅ CORREÇÃO: Extrair campos da estrutura correta da API
    $fabricante = $r['MARCA'] ?? $r['marca'] ?? $r['fabricante'] ?? '';
    $veiculo = $r['MODELO'] ?? $r['modelo'] ?? $r['veiculo'] ?? '';
    $modelo = $r['VERSAO'] ?? $r['versao'] ?? $r['SUBMODELO'] ?? '';
    $anoMod = $r['anoModelo'] ?? $r['ano'] ?? $r['ano_fabricacao'] ?? '';
    
    fwrite($logs, "🔍 EXTRACT VEHICLE - Campos extraídos:" . PHP_EOL);
    fwrite($logs, "   - fabricante: '{$fabricante}'" . PHP_EOL);
    fwrite($logs, "   - veiculo: '{$veiculo}'" . PHP_EOL);
    fwrite($logs, "   - modelo: '{$modelo}'" . PHP_EOL);
    fwrite($logs, "   - anoMod: '{$anoMod}'" . PHP_EOL);
    
    $marcaTxt = implode(' / ', array_filter([$fabricante, $veiculo, $modelo]));
    $anoModelo = substr(onlyDigits((string)$anoMod), 0, 4);
    
    fwrite($logs, "🔍 EXTRACT VEHICLE - Resultado final:" . PHP_EOL);
    fwrite($logs, "   - marcaTxt: '{$marcaTxt}'" . PHP_EOL);
    fwrite($logs, "   - anoModelo: '{$anoModelo}'" . PHP_EOL);
    
    return [
        'marcaTxt' => $marcaTxt,
        'anoModelo' => $anoModelo
    ];
}

// Função para validar placa na API (corrigida para funcionar igual ao JavaScript)
function validarPlacaApi($placa) {
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
    fwrite($logs, "🌐 API CALL - Placa: {$raw}, HTTP Code: {$httpCode}, cURL Error: {$curlError}" . PHP_EOL);
    fwrite($logs, "🌐 API Response: " . $response . PHP_EOL);
    
    // ✅ AGORA igual ao JavaScript: trata erros como 'erro_api'
    if ($response === false || $curlError || $httpCode === 0) {
        if ($httpCode === 0 && strpos($curlError, 'timeout') !== false) {
            fwrite($logs, "⏰ API TIMEOUT - Placa {$raw} não respondeu em 10s - fazendo validação local" . PHP_EOL);
        } else {
            fwrite($logs, "❌ API error/curl error - fazendo validação local" . PHP_EOL);
        }
        return ['ok' => false, 'reason' => 'erro_api'];
    }
    
    if ($httpCode !== 200) {
        fwrite($logs, "❌ HTTP Error: " . $httpCode . " - fazendo validação local" . PHP_EOL);
        return ['ok' => false, 'reason' => 'erro_api'];
    }
    
    $json = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        fwrite($logs, "❌ JSON Decode Error: " . json_last_error_msg() . " - fazendo validação local" . PHP_EOL);
        return ['ok' => false, 'reason' => 'erro_api'];
    }
    
    // ✅ AGORA igual ao JavaScript: usa !!j?.ok para determinar se deu certo
    $ok = !empty($json['ok']);
    fwrite($logs, "🔍 API Result - OK: " . ($ok ? 'true' : 'false') . PHP_EOL);
    fwrite($logs, "🔍 API JSON Data: " . print_r($json, true) . PHP_EOL);
    
    if ($ok) {
        // ✅ API retornou dados válidos
        $parsedData = extractVehicleFromApiBrasil($json['data']);
        fwrite($logs, "🎉 API SUCESSO - Dados reais do veículo obtidos:" . PHP_EOL);
        fwrite($logs, "   - marcaTxt: '{$parsedData['marcaTxt']}'" . PHP_EOL);
        fwrite($logs, "   - anoModelo: '{$parsedData['anoModelo']}'" . PHP_EOL);
        fwrite($logs, "   - Status: ✅ DADOS REAIS DA API - Prioridade máxima!" . PHP_EOL);
        
        return [
            'ok' => true,
            'reason' => 'ok',
            'parsed' => $parsedData
        ];
    } else {
        // ❌ API não encontrou a placa
        fwrite($logs, "⚠️  API não encontrou a placa - reason: nao_encontrada" . PHP_EOL);
        return [
            'ok' => false,
            'reason' => 'nao_encontrada',
            'parsed' => ['marcaTxt' => '', 'anoModelo' => '']
        ];
    }
}

// Função para validação local robusta quando a API falha
function validarPlacaLocal($placa) {
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
        fwrite($logs, "🔍 RESULTADO DA VALIDAÇÃO DA PLACA {$placa}:" . PHP_EOL);
        fwrite($logs, "   - OK: " . ($resultadoValidacao['ok'] ? 'true' : 'false') . PHP_EOL);
        fwrite($logs, "   - Reason: " . $resultadoValidacao['reason'] . PHP_EOL);
        fwrite($logs, "   - Parsed: " . print_r($resultadoValidacao['parsed'], true) . PHP_EOL);
        
        if ($resultadoValidacao['ok'] === true) {
            // ✅ DADOS REAIS DA API - Prioridade máxima
            $veiculo = $resultadoValidacao['parsed']['marcaTxt'];
            $anoModelo = $resultadoValidacao['parsed']['anoModelo'];
            fwrite($logs, "✅ PLACA VÁLIDA NA API - DADOS REAIS EXTRAÍDOS:" . PHP_EOL);
            fwrite($logs, "   - Veículo: '{$veiculo}'" . PHP_EOL);
            fwrite($logs, "   - Ano: '{$anoModelo}'" . PHP_EOL);
            fwrite($logs, "   - Status: Dados reais da API serão enviados para o EspoCRM" . PHP_EOL);
        } else {
            // Se a API falhou, tenta validação local como fallback
            if ($resultadoValidacao['reason'] === 'erro_api') {
                fwrite($logs, "⚠️  API FALHOU - Tentando validação local como fallback..." . PHP_EOL);
                $resultadoLocal = validarPlacaLocal($placa);
                
                if ($resultadoLocal['ok']) {
                    // ⚠️ DADOS GENÉRICOS DA VALIDAÇÃO LOCAL (apenas quando API falha)
                    $veiculo = $resultadoLocal['parsed']['marcaTxt'];
                    $anoModelo = $resultadoLocal['parsed']['anoModelo'];
                    fwrite($logs, "⚠️  VALIDAÇÃO LOCAL BEM-SUCEDIDA (FALLBACK) - Dados genéricos:" . PHP_EOL);
                    fwrite($logs, "   - Veículo: '{$veiculo}'" . PHP_EOL);
                    fwrite($logs, "   - Ano: '{$anoModelo}'" . PHP_EOL);
                    fwrite($logs, "   - Status: Dados genéricos serão enviados para o EspoCRM" . PHP_EOL);
                } else {
                    $veiculo = 'ERRO NA VALIDACAO';
                    $anoModelo = '';
                    fwrite($logs, "❌ VALIDAÇÃO LOCAL FALHOU - Definindo veículo como 'ERRO NA VALIDACAO'" . PHP_EOL);
                }
            } elseif ($resultadoValidacao['reason'] === 'formato') {
                $veiculo = 'FORMATO INVALIDO';
                $anoModelo = '';
                fwrite($logs, "❌ FORMATO DE PLACA INVÁLIDO - Definindo veículo como 'FORMATO INVALIDO'" . PHP_EOL);
            } elseif ($resultadoValidacao['reason'] === 'nao_encontrada') {
                $veiculo = 'PLACA NAO LOCALIZADA';
                $anoModelo = '';
                fwrite($logs, "⚠️  PLACA NÃO ENCONTRADA NA API - Definindo veículo como 'PLACA NAO LOCALIZADA'" . PHP_EOL);
            } else {
                $veiculo = 'ERRO NA VALIDACAO';
                $anoModelo = '';
                fwrite($logs, "❌ ERRO NA VALIDAÇÃO DA PLACA: " . $resultadoValidacao['reason'] . PHP_EOL);
            }
        }
    } catch (Exception $e) {
        fwrite($logs, "❌ EXCEÇÃO NA VALIDAÇÃO DA PLACA: " . $e->getMessage() . " - Tentando validação local de emergência..." . PHP_EOL);
        
        // Fallback para validação local em caso de exceção
        try {
            $resultadoLocal = validarPlacaLocal($placa);
            if ($resultadoLocal['ok']) {
                // ⚠️ DADOS GENÉRICOS DE EMERGÊNCIA (apenas em caso de exceção)
                $veiculo = $resultadoLocal['parsed']['marcaTxt'];
                $anoModelo = $resultadoLocal['parsed']['anoModelo'];
                fwrite($logs, "⚠️  VALIDAÇÃO LOCAL DE EMERGÊNCIA BEM-SUCEDIDA - Dados genéricos:" . PHP_EOL);
                fwrite($logs, "   - Veículo: '{$veiculo}'" . PHP_EOL);
                fwrite($logs, "   - Ano: '{$anoModelo}'" . PHP_EOL);
                fwrite($logs, "   - Status: Dados genéricos de emergência serão enviados para o EspoCRM" . PHP_EOL);
            } else {
                $veiculo = 'ERRO NA VALIDACAO';
                $anoModelo = '';
                fwrite($logs, "❌ VALIDAÇÃO LOCAL DE EMERGÊNCIA FALHOU" . PHP_EOL);
            }
        } catch (Exception $e2) {
            fwrite($logs, "❌ ERRO NA VALIDAÇÃO LOCAL DE EMERGÊNCIA: " . $e2->getMessage() . PHP_EOL);
            $veiculo = 'ERRO NA VALIDACAO';
            $anoModelo = '';
        }
    }
} else {
    fwrite($logs, "ℹ️  Nenhuma placa informada para validação" . PHP_EOL);
}

// Log final dos valores que serão enviados para o EspoCRM
fwrite($logs, "📋 VALORES FINAIS PARA ESPOCRM:" . PHP_EOL);
fwrite($logs, "   - cMarca: '{$veiculo}'" . PHP_EOL);
fwrite($logs, "   - cAnoMod: '{$anoModelo}'" . PHP_EOL);

// --- 7. Envio ao EspoCRM ---
$client = new EspoApiClient('https://travelangels.com.br');
$client->setApiKey('d5bcb42f62d1d96f8090a1002b792335');

try {
    $response = $client->request('POST', 'Lead', [
        'firstName'      => $name,
        'cCelular'       => $cel,
        'cCpftext'       => $cpf,
        'cPlaca'         => $placa,
        'addressPostalCode' => $cep,
        'cGclid'         => $gclid,
        'emailAddress'   => $email,
        'cMarca'         => $veiculo,
        'cAnoMod'        => $anoModelo,
    ]);
    fwrite($logs, "API Response: " . print_r($response, true) . PHP_EOL);

} catch (Exception $e) {
    fwrite($logs, "API Error: " . $e->getMessage() . PHP_EOL);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    fclose($logs);
    exit();
}

// --- 8. Finalização ---
fwrite($logs, "Script finished." . PHP_EOL . PHP_EOL);
fclose($logs);

header('Content-Type: application/json');
echo json_encode(['status' => 'success']);
?>
