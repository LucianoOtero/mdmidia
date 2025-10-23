<?php

/**
 * GERADOR DE CHAVES PARA ESPOCRM DE DESENVOLVIMENTO
 * dev/scripts/generate_espocrm_keys.php
 * 
 * Script para gerar chaves de API e configurar ambiente de desenvolvimento
 */

// Configurações do ambiente de desenvolvimento
$DEV_ESPOCRM_CONFIG = [
    'url' => 'https://dev.flyingdonkeys.com.br',
    'admin_email' => 'admin@flyingdonkeys.com.br',
    'admin_password' => '', // Será solicitado
    'api_user_name' => 'API Webhook Dev',
    'api_user_email' => 'api-dev@flyingdonkeys.com.br',
    'api_user_password' => '',
    'api_key' => '',
    'allowed_ips' => ['*'] // Para desenvolvimento
];

echo "🔧 CONFIGURAÇÃO DO ESPOCRM DE DESENVOLVIMENTO\n";
echo "============================================\n\n";

// Função para gerar chave aleatória
function generateApiKey($length = 32)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $key;
}

// Função para gerar senha segura
function generateSecurePassword($length = 16)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// Gerar credenciais
$apiKey = generateApiKey(32);
$apiPassword = generateSecurePassword(16);

echo "📋 CREDENCIAIS GERADAS:\n";
echo "======================\n";
echo "🔑 API Key: " . $apiKey . "\n";
echo "🔒 API Password: " . $apiPassword . "\n";
echo "📧 API User Email: " . $DEV_ESPOCRM_CONFIG['api_user_email'] . "\n";
echo "👤 API User Name: " . $DEV_ESPOCRM_CONFIG['api_user_name'] . "\n\n";

// Salvar configurações em arquivo
$configContent = "<?php\n";
$configContent .= "/**\n";
$configContent .= " * CONFIGURAÇÕES ESPOCRM DESENVOLVIMENTO\n";
$configContent .= " * Gerado em: " . date('Y-m-d H:i:s') . "\n";
$configContent .= " */\n\n";
$configContent .= "\$DEV_ESPOCRM_CREDENTIALS = [\n";
$configContent .= "    'url' => '{$DEV_ESPOCRM_CONFIG['url']}',\n";
$configContent .= "    'api_key' => '{$apiKey}',\n";
$configContent .= "    'api_user_email' => '{$DEV_ESPOCRM_CONFIG['api_user_email']}',\n";
$configContent .= "    'api_user_password' => '{$apiPassword}',\n";
$configContent .= "    'api_user_name' => '{$DEV_ESPOCRM_CONFIG['api_user_name']}',\n";
$configContent .= "    'created_at' => '" . date('Y-m-d H:i:s') . "',\n";
$configContent .= "    'environment' => 'development'\n";
$configContent .= "];\n\n";
$configContent .= "// Teste de conectividade\n";
$configContent .= "function testEspoCrmConnection() {\n";
$configContent .= "    global \$DEV_ESPOCRM_CREDENTIALS;\n";
$configContent .= "    \n";
$configContent .= "    \$url = \$DEV_ESPOCRM_CREDENTIALS['url'] . '/api/v1/App/user'; \n";
$configContent .= "    \n";
$configContent .= "    \$headers = [\n";
$configContent .= "        'X-Api-Key: ' . \$DEV_ESPOCRM_CREDENTIALS['api_key'],\n";
$configContent .= "        'Content-Type: application/json'\n";
$configContent .= "    ];\n";
$configContent .= "    \n";
$configContent .= "    \$ch = curl_init();\n";
$configContent .= "    curl_setopt(\$ch, CURLOPT_URL, \$url);\n";
$configContent .= "    curl_setopt(\$ch, CURLOPT_HTTPHEADER, \$headers);\n";
$configContent .= "    curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);\n";
$configContent .= "    curl_setopt(\$ch, CURLOPT_SSL_VERIFYPEER, false);\n";
$configContent .= "    curl_setopt(\$ch, CURLOPT_TIMEOUT, 30);\n";
$configContent .= "    \n";
$configContent .= "    \$response = curl_exec(\$ch);\n";
$configContent .= "    \$httpCode = curl_getinfo(\$ch, CURLINFO_HTTP_CODE);\n";
$configContent .= "    curl_close(\$ch);\n";
$configContent .= "    \n";
$configContent .= "    return [\n";
$configContent .= "        'http_code' => \$httpCode,\n";
$configContent .= "        'response' => \$response,\n";
$configContent .= "        'success' => \$httpCode >= 200 && \$httpCode < 300\n";
$configContent .= "    ];\n";
$configContent .= "}\n";

// Salvar arquivo de configuração
file_put_contents(__DIR__ . '/../config/espocrm_dev_credentials.php', $configContent);

echo "✅ CONFIGURAÇÕES SALVAS:\n";
echo "========================\n";
echo "📁 Arquivo: dev/config/espocrm_dev_credentials.php\n\n";

echo "📋 PRÓXIMOS PASSOS:\n";
echo "===================\n";
echo "1. Acesse: {$DEV_ESPOCRM_CONFIG['url']}\n";
echo "2. Faça login como administrador\n";
echo "3. Vá em Administration → Users\n";
echo "4. Crie usuário: {$DEV_ESPOCRM_CONFIG['api_user_name']}\n";
echo "5. Email: {$DEV_ESPOCRM_CONFIG['api_user_email']}\n";
echo "6. Senha: {$apiPassword}\n";
echo "7. Vá em Administration → API Users\n";
echo "8. Crie API User com a chave: {$apiKey}\n\n";

echo "🧪 TESTE DE CONECTIVIDADE:\n";
echo "==========================\n";
echo "Execute: php dev/scripts/test_espocrm_connection.php\n\n";

echo "🔧 INTEGRAÇÃO COM WEBHOOKS:\n";
echo "============================\n";
echo "As credenciais serão automaticamente carregadas nos webhooks de desenvolvimento.\n";
echo "Arquivo de configuração: dev/config/dev_config.php\n\n";
