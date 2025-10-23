<?php

/**
 * VERIFICAÇÃO DE CONFIGURAÇÃO ESPOCRM
 * dev/scripts/check_espocrm_config.php
 * 
 * Script para verificar se o EspoCRM está configurado corretamente
 */

$ESPOCRM_URL = 'https://dev.flyingdonkeys.com.br';

echo "🔍 VERIFICAÇÃO DE CONFIGURAÇÃO ESPOCRM\n";
echo "=====================================\n\n";

// Função para fazer requisição simples
function makeRequest($url, $method = 'GET', $headers = []) {
    $ch = curl_init();
    
    $defaultHeaders = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
    ];
    
    $allHeaders = array_merge($defaultHeaders, $headers);
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'final_url' => $finalUrl,
        'error' => $error
    ];
}

echo "🌐 TESTANDO ACESSO BÁSICO:\n";
echo "==========================\n";

// Testar acesso básico
$result = makeRequest($ESPOCRM_URL);
echo "URL: {$ESPOCRM_URL}\n";
echo "HTTP Code: {$result['http_code']}\n";
echo "Final URL: {$result['final_url']}\n";

if ($result['error']) {
    echo "Error: {$result['error']}\n";
}

if (strpos($result['response'], 'EspoCRM') !== false) {
    echo "✅ EspoCRM detectado na resposta!\n";
} else {
    echo "❌ EspoCRM não detectado na resposta\n";
}

echo "\n";

echo "🔍 VERIFICANDO ESTRUTURA DE DIRETÓRIOS:\n";
echo "======================================\n";

$paths = [
    '/api',
    '/api/v1',
    '/api/v1/App',
    '/api/v1/User',
    '/api/v1/Lead',
    '/api/v1/Opportunity',
    '/install',
    '/setup',
    '/admin',
    '/application',
    '/client'
];

foreach ($paths as $path) {
    $url = $ESPOCRM_URL . $path;
    $result = makeRequest($url);
    
    echo "🔗 {$path}: HTTP {$result['http_code']}";
    
    if ($result['http_code'] === 200) {
        echo " ✅";
    } elseif ($result['http_code'] === 404) {
        echo " ❌";
    } elseif ($result['http_code'] === 403) {
        echo " 🔒";
    } elseif ($result['http_code'] === 301 || $result['http_code'] === 302) {
        echo " ↪️";
    }
    
    echo "\n";
}

echo "\n";

echo "🧪 TESTANDO ENDPOINTS DE INSTALAÇÃO:\n";
echo "====================================\n";

$installEndpoints = [
    '/install',
    '/setup',
    '/install/index.php',
    '/setup/index.php',
    '/api/v1/App/install',
    '/api/v1/App/setup'
];

foreach ($installEndpoints as $endpoint) {
    $url = $ESPOCRM_URL . $endpoint;
    $result = makeRequest($endpoint);
    
    echo "🔗 {$endpoint}: HTTP {$result['http_code']}";
    
    if ($result['http_code'] === 200) {
        echo " ✅ (Possível página de instalação)";
    }
    
    echo "\n";
}

echo "\n";

echo "📋 INFORMAÇÕES DO SERVIDOR:\n";
echo "===========================\n";

// Verificar headers do servidor
$result = makeRequest($ESPOCRM_URL);
$headers = get_headers($ESPOCRM_URL, 1);

if ($headers) {
    foreach ($headers as $key => $value) {
        if (is_string($key)) {
            echo "{$key}: {$value}\n";
        }
    }
}

echo "\n";

echo "🔧 POSSÍVEIS SOLUÇÕES:\n";
echo "======================\n";
echo "1. Verificar se o EspoCRM está instalado corretamente\n";
echo "2. Verificar se a API está habilitada nas configurações\n";
echo "3. Verificar se o usuário admin existe e tem permissões\n";
echo "4. Verificar se o arquivo .htaccess está configurado\n";
echo "5. Verificar se o mod_rewrite está habilitado\n";
echo "6. Verificar se o PHP está configurado corretamente\n\n";

echo "💡 PRÓXIMOS PASSOS:\n";
echo "===================\n";
echo "1. Acesse {$ESPOCRM_URL} no navegador\n";
echo "2. Verifique se consegue fazer login\n";
echo "3. Vá em Administration → Settings → API\n";
echo "4. Verifique se a API está habilitada\n";
echo "5. Verifique se o usuário admin tem permissões de API\n";

?>
