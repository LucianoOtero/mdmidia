<?php

/**
 * DEPLOY AUTOMÁTICO DO SIMULADOR OCTADESK
 * Script para ser executado no servidor
 */

echo "🚀 DEPLOY AUTOMÁTICO DO SIMULADOR OCTADESK\n";
echo "==========================================\n\n";

$basePath = __DIR__ . '/dev/octadesk-simulator';
$serverPath = '/dev/octadesk-simulator';

echo "📁 Caminho base: {$basePath}\n";
echo "🌐 Caminho servidor: {$serverPath}\n\n";

// Verificar se estamos no servidor correto
if (!file_exists($basePath)) {
    echo "❌ ERRO: Diretório {$basePath} não encontrado!\n";
    echo "Execute este script no diretório raiz do servidor.\n";
    exit(1);
}

echo "✅ Diretório encontrado!\n\n";

// Criar diretórios necessários
echo "📁 CRIANDO DIRETÓRIOS...\n";
echo "========================\n";

$dirs = [
    $basePath . '/data',
    dirname($basePath) . '/logs'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Criado: {$dir}\n";
        } else {
            echo "❌ Erro ao criar: {$dir}\n";
        }
    } else {
        echo "✅ Já existe: {$dir}\n";
    }
}

echo "\n📄 VERIFICANDO ARQUIVOS...\n";
echo "===========================\n";

$files = [
    'index.php' => 'Simulador principal',
    'monitor.html' => 'Interface de monitoramento',
    '.htaccess' => 'Configuração Apache'
];

foreach ($files as $file => $description) {
    $filePath = $basePath . '/' . $file;
    if (file_exists($filePath)) {
        $size = filesize($filePath);
        echo "✅ {$file} - {$description} ({$size} bytes)\n";
    } else {
        echo "❌ {$file} - AUSENTE!\n";
    }
}

echo "\n🔧 CONFIGURANDO PERMISSÕES...\n";
echo "==============================\n";

// Configurar permissões
$permissions = [
    $basePath => 0755,
    $basePath . '/data' => 0755,
    dirname($basePath) . '/logs' => 0755
];

foreach ($permissions as $path => $perm) {
    if (is_dir($path)) {
        if (chmod($path, $perm)) {
            echo "✅ Permissões configuradas: {$path}\n";
        } else {
            echo "⚠️ Aviso: Não foi possível configurar permissões para: {$path}\n";
        }
    }
}

echo "\n🧪 TESTANDO SIMULADOR...\n";
echo "=========================\n";

// Testar se o simulador responde
$testUrl = 'https://bpsegurosimediato.com.br' . $serverPath . '/api/v1/health';

echo "🔗 Testando: {$testUrl}\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Api-Key: dev_octadesk_key_12345',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Simulador funcionando! HTTP {$httpCode}\n";
    echo "📊 Resposta: " . substr($response, 0, 100) . "...\n";
} else {
    echo "⚠️ Simulador pode não estar funcionando. HTTP {$httpCode}\n";
    if ($error) {
        echo "❌ Erro cURL: {$error}\n";
    }
}

echo "\n🎉 DEPLOY CONCLUÍDO!\n";
echo "====================\n";
echo "📋 PRÓXIMOS PASSOS:\n";
echo "1. Acesse: https://bpsegurosimediato.com.br{$serverPath}/monitor.html\n";
echo "2. Teste os endpoints da API\n";
echo "3. Configure os webhooks de desenvolvimento\n";
echo "4. Monitore os logs em tempo real\n\n";

echo "🔑 API KEYS VÁLIDAS:\n";
echo "- dev_octadesk_key_12345\n";
echo "- test_octadesk_key_67890\n";
echo "- simulator_octadesk_key\n\n";

echo "📊 ENDPOINTS DISPONÍVEIS:\n";
echo "- POST /api/v1/contacts - Criar contato\n";
echo "- GET  /api/v1/contacts - Listar contatos\n";
echo "- POST /api/v1/conversations - Criar conversa\n";
echo "- GET  /api/v1/conversations - Listar conversas\n";
echo "- POST /api/v1/messages - Enviar mensagem\n";
echo "- GET  /api/v1/health - Health check\n";
echo "- GET  /api/v1/info - Informações do simulador\n";
