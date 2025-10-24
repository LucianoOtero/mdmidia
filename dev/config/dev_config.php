<?php

/**
 * CONFIGURAÇÃO AMBIENTE DE DESENVOLVIMENTO
 * mdmidia/dev/config/dev_config.php
 * 
 * Configurações específicas para o ambiente de desenvolvimento local
 */

// Configurações de ambiente
$is_dev = true;
$environment = 'development';

// URLs dos endpoints de desenvolvimento
$DEV_WEBHOOK_URLS = [
    'travelangels' => 'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php',
    'octadesk' => 'https://bpsegurosimediato.com.br/dev/webhooks/add_webflow_octa_dev.php',
    'collect_chat' => 'https://bpsegurosimediato.com.br/dev/webhooks/add_collect_chat.php',
    'health' => 'https://bpsegurosimediato.com.br/dev/health.php'
];

// Secret keys para desenvolvimento (usando secrets reais do Webflow)
$DEV_WEBFLOW_SECRETS = [
    'travelangels' => 'b2eaccc6360243534828bec688e6f719565d912f11bc1fb2d718417de07a200b',
    'octadesk' => '9ce84b44c92ad0130999a4142eb66391be2215f68c5b413722439a59d5183ade',
    'collect_chat' => '1601e39b8b4940a5ac7a49b80c8f05571ef6d408908b76b76d314dcdbe061a45'
];

// Configurações de logging para desenvolvimento
$DEV_LOGGING = [
    'travelangels' => __DIR__ . '/../logs/travelangels_dev.txt',
    'octadesk' => __DIR__ . '/../logs/octadesk_dev.txt',
    'collect_chat' => __DIR__ . '/../logs/collect_chat_dev.txt',
    'general' => __DIR__ . '/../logs/general_dev.txt',
    'errors' => __DIR__ . '/../logs/errors_dev.txt',
    'max_log_size' => 1024 * 1024, // 1MB
    'log_backups' => 3
];

// Configurações de API V2 para desenvolvimento
$DEV_API_V2_CONFIG = [
    'signature_validation' => true,
    'fallback_on_error' => true, // Continuar processamento se signature falhar
    'detailed_logging' => true,
    'test_mode' => true,
    'mock_responses' => false // Usar respostas reais mas com logs detalhados
];

// Configurações de CRM/OctaDesk para desenvolvimento
$DEV_CRM_CONFIG = [
    'travelangels_api_url' => 'https://dev.travelangels.com.br', // URL de dev ou mock
    'travelangels_api_key' => 'dev_travelangels_key',
    'flyingdonkeys_api_url' => 'https://dev.flyingdonkeys.com.br', // ✅ URL REAL de desenvolvimento
    'flyingdonkeys_api_key' => '', // Será carregado do arquivo de credenciais
    'octadesk_api_url' => 'https://bpsegurosimediato.com.br/dev/octadesk-simulator', // ✅ SIMULADOR OCTADESK
    'octadesk_api_key' => 'dev_octadesk_key_12345' // ✅ API Key do simulador
];

// Carregar credenciais do EspoCRM de desenvolvimento se existirem
$espocrm_credentials_file = __DIR__ . '/espocrm_dev_credentials.php';
if (file_exists($espocrm_credentials_file)) {
    require_once $espocrm_credentials_file;
    if (isset($DEV_ESPOCRM_CREDENTIALS)) {
        $DEV_CRM_CONFIG['flyingdonkeys_api_key'] = $DEV_ESPOCRM_CREDENTIALS['api_key'];
        logDev('INFO', 'Credenciais EspoCRM de desenvolvimento carregadas', [
            'url' => $DEV_ESPOCRM_CREDENTIALS['url'],
            'api_key_length' => strlen($DEV_ESPOCRM_CREDENTIALS['api_key'])
        ], 'general');
    }
}

// Outras configurações de desenvolvimento
$DEV_SETTINGS = [
    'debug_mode' => true,
    'display_errors' => true,
    'error_reporting' => E_ALL,
    'environment_name' => 'development',
    'timezone' => 'America/Sao_Paulo',
    'charset' => 'UTF-8'
];

// Configurações específicas para Collect Chat
$DEV_COLLECT_CHAT_CONFIG = [
    'source' => 'Collect Chat',
    'webpage' => 'collect.chat',
    'log_file' => 'collect_chat_dev.txt',
    'fields' => [
        'NAME',
        'NUMBER',
        'CPF',
        'PLACA',
        'CEP',
        'EMAIL',
        'gclid'
    ]
];

// Configurações específicas para TravelAngels
$DEV_TRAVELANGELS_CONFIG = [
    'source' => 'Site', // ✅ Valor válido no EspoCRM
    'webpage' => 'bpsegurosimediato.com.br',
    'log_file' => 'travelangels_dev.txt',
    'create_opportunity' => true,
    'opportunity_source_field' => 'leadSource' // ✅ CORRETO para Opportunity
];

// Configurações específicas para OctaDesk
$DEV_OCTADESK_CONFIG = [
    'log_file' => 'octadesk_dev.txt',
    'test_mode' => true,
    'simulate_responses' => true
];

// Função para log específico de desenvolvimento
function logDev($level, $message, $data = null, $webhook = 'general')
{
    global $DEV_LOGGING, $is_dev;

    if (!$is_dev) return;

    $log_file = $DEV_LOGGING[$webhook] ?? $DEV_LOGGING['general'];
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message";

    if ($data !== null) {
        $log_entry .= " | Data: " . json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    $log_entry .= PHP_EOL;

    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// Função para validar ambiente de desenvolvimento
function validateDevEnvironment()
{
    global $is_dev, $environment;

    if (!$is_dev) {
        http_response_code(403);
        exit('Acesso negado: Este endpoint é apenas para desenvolvimento');
    }

    return true;
}

// Configurar ambiente PHP para desenvolvimento
if ($is_dev) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    date_default_timezone_set($DEV_SETTINGS['timezone']);
}

// Log de inicialização do ambiente de desenvolvimento
logDev('INFO', 'Ambiente de desenvolvimento inicializado', [
    'environment' => $environment,
    'timestamp' => date('Y-m-d H:i:s'),
    'config_loaded' => true
], 'general');
