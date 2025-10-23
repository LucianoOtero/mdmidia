<?php

/**
 * CONFIGURAÇÕES ESPOCRM DESENVOLVIMENTO
 * Gerado em: 2025-10-23 18:47:49
 */

$DEV_ESPOCRM_CREDENTIALS = [
    'url' => 'https://dev.flyingdonkeys.com.br',
    'api_key' => 'd538e606685cecd0d76746906468eba4',
    'api_user_email' => 'api-dev@flyingdonkeys.com.br',
    'api_user_password' => '4vJMGl9%@DtELFqS',
    'api_user_name' => 'API Webhook Dev',
    'created_at' => '2025-10-23 18:47:49',
    'environment' => 'development'
];

// Teste de conectividade
function testEspoCrmConnection()
{
    global $DEV_ESPOCRM_CREDENTIALS;

    $url = $DEV_ESPOCRM_CREDENTIALS['url'] . '/api/v1/App/user';

    $headers = [
        'X-Api-Key: ' . $DEV_ESPOCRM_CREDENTIALS['api_key'],
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'http_code' => $httpCode,
        'response' => $response,
        'success' => $httpCode >= 200 && $httpCode < 300
    ];
}
