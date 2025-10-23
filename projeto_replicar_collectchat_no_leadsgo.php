<?php
require_once('class.php');

// ============================================================================
// PROJETO: REPLICAR LÓGICA AVANÇADA DO ADD_COLLECTCHAT NO ADD_LEADSGO
// ============================================================================
// 
// OBJETIVO: Implementar no add_leadsgo as melhorias encontradas no add_collectchat:
// 
// 1. ✅ VALIDAÇÃO AVANÇADA DE PLACA
//    - Validação de formato (antigo e Mercosul)
//    - Integração com API externa para dados reais do veículo
//    - Fallback para validação local quando API falha
//    - Tratamento robusto de erros e timeouts
//
// 2. ✅ TRATAMENTO MELHORADO DE DUPLICATAS
//    - Busca por email quando lead duplicado
//    - Atualização de lead existente em vez de erro
//    - Tratamento de exceções do EspoCRM
//
// 3. ✅ CRIAÇÃO AUTOMÁTICA DE OPORTUNIDADES
//    - Criação automática de oportunidade após lead
//    - Mapeamento completo de campos
//    - Tratamento de duplicatas de oportunidade
//
// 4. ✅ LOGGING AVANÇADO
//    - Logs com timestamp detalhados
//    - Debug completo de requisições
//    - Rastreamento de cada etapa do processo
//
// 5. ✅ TRATAMENTO ROBUSTO DE DADOS
//    - Múltiplos formatos de entrada (JSON, form-data)
//    - Validação de dados antes do processamento
//    - Fallbacks para dados ausentes
//
// ============================================================================

// Função para log com timestamp
function logWithTimestamp($logs, $message) {
    $timestamp = date('Y-m-d H:i:s');
    fwrite($logs, "[$timestamp] $message" . PHP_EOL);
}

// Função para buscar lead por email
function findLeadByEmail($email, $client, $logs) {
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

// ============================================================================
// FUNÇÕES DE VALIDAÇÃO DE PLACA (REPLICADAS DO COLLECTCHAT)
// ============================================================================

// Função para converter para maiúsculas e remover espaços
function toUpperNospace($str) {
    return strtoupper(trim($str));
}

// Função para extrair apenas dígitos
function onlyDigits($str) {
    return preg_replace('/[^0-9]/', '', $str);
}

// Função para validar formato da placa
function validarPlacaFormato($p) {
    $p = toUpperNospace($p);
    $p = preg_replace('/[^A-Z0-9]/', '', $p);

    $antigo = '/^[A-Z]{3}[0-9]{4}$/';
    $mercosul = '/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/';

    return preg_match($antigo, $p) || preg_match($mercosul, $p);
}

// Função para extrair dados do veículo da API
function extractVehicleFromApiBrasil($apiJson) {
    global $logs;

    logWithTimestamp($logs, "🔍 EXTRACT VEHICLE - API JSON recebido: " . print_r($apiJson, true));

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

// Função para validar placa na API
function validarPlacaApi($placa) {
    $raw = toUpperNospace($placa);
    $raw = preg_replace('/[^A-Z0-9]/', '', $raw);

    if (!validarPlacaFormato($raw)) {
        return ['ok' => false, 'reason' => 'formato'];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://mdmidia.com.br/api/placa-validate.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['placa' => $raw]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    global $logs;
    logWithTimestamp($logs, "🌐 API CALL - Placa: {$raw}, HTTP Code: {$httpCode}, cURL Error: {$curlError}");
    logWithTimestamp($logs, "🌐 API Response: " . $response);

    if ($response === false || $curlError || $httpCode === 0) {
        if ($httpCode === 0 && strpos($curlError, 'timeout') !== false) {
            logWithTimestamp($logs, "⏰ API TIMEOUT - Placa {$raw} não respondeu em 20s - fazendo validação local");
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

    $ok = !empty($json['ok']);
    logWithTimestamp($logs, "🔍 API Result - OK: " . ($ok ? 'true' : 'false'));
    logWithTimestamp($logs, "🔍 API JSON Data: " . print_r($json, true));

    if ($ok) {
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
        logWithTimestamp($logs, "⚠️  API não encontrou a placa - reason: nao_encontrada");
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

    if (strlen($raw) >= 7) {
        if (preg_match('/^[A-Z]{3}[0-9]{4}$/', $raw)) {
            return [
                'ok' => true,
                'reason' => 'ok_local',
                'parsed' => [
                    'marcaTxt' => 'VEÍCULO VALIDADO (FORMATO ANTIGO)',
                    'anoModelo' => date('Y')
                ]
            ];
        } elseif (preg_match('/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/', $raw)) {
            return [
                'ok' => true,
                'reason' => 'ok_local',
                'parsed' => [
                    'marcaTxt' => 'VEÍCULO VALIDADO (FORMATO MERCOSUL)',
                    'anoModelo' => date('Y')
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

// ============================================================================
// IMPLEMENTAÇÃO DA LÓGICA AVANÇADA PARA LEADSGO
// ============================================================================

echo "=== IMPLEMENTAÇÃO DA LÓGICA AVANÇADA DO COLLECTCHAT NO LEADSGO ===\n\n";

echo "📋 MELHORIAS IDENTIFICADAS:\n";
echo "1. ✅ Validação avançada de placa com API externa\n";
echo "2. ✅ Tratamento robusto de duplicatas\n";
echo "3. ✅ Criação automática de oportunidades\n";
echo "4. ✅ Logging detalhado com timestamp\n";
echo "5. ✅ Tratamento de múltiplos formatos de entrada\n";
echo "6. ✅ Fallbacks para dados ausentes\n\n";

echo "🔧 FUNÇÕES IMPLEMENTADAS:\n";
echo "- toUpperNospace(): Normalização de strings\n";
echo "- onlyDigits(): Extração de dígitos\n";
echo "- validarPlacaFormato(): Validação de formato de placa\n";
echo "- extractVehicleFromApiBrasil(): Extração de dados da API\n";
echo "- validarPlacaApi(): Validação via API externa\n";
echo "- validarPlacaLocal(): Validação local como fallback\n";
echo "- findLeadByEmail(): Busca de leads duplicados\n";
echo "- logWithTimestamp(): Logging com timestamp\n\n";

echo "📝 PRÓXIMOS PASSOS PARA IMPLEMENTAÇÃO:\n";
echo "1. Integrar funções de validação de placa no add_leadsgo\n";
echo "2. Implementar tratamento de duplicatas melhorado\n";
echo "3. Adicionar criação automática de oportunidades\n";
echo "4. Melhorar sistema de logging\n";
echo "5. Testar integração completa\n\n";

echo "✅ ARQUIVO DE PROJETO CRIADO COM SUCESSO!\n";
echo "📁 Arquivo: projeto_replicar_collectchat_no_leadsgo.php\n";
echo "🎯 Objetivo: Implementar melhorias do collectchat no leadsgo\n";
echo "📅 Data: " . date('Y-m-d H:i:s') . "\n\n";

echo "=== FIM DO PROJETO ===\n";




