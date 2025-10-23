<?php

/**
 * DEPLOY DO SIMULADOR OCTADESK
 * dev/scripts/deploy_octadesk_simulator.php
 * 
 * Script para fazer deploy do simulador para o servidor
 */

echo "🚀 DEPLOY DO SIMULADOR OCTADESK\n";
echo "===============================\n\n";

$localPath = __DIR__ . '/../octadesk-simulator';
$serverPath = '/dev/octadesk-simulator';

echo "📁 Caminho local: {$localPath}\n";
echo "🌐 Caminho servidor: {$serverPath}\n\n";

// Verificar se os arquivos existem
$files = [
    'index.php',
    'monitor.html',
    '.htaccess'
];

echo "🔍 VERIFICANDO ARQUIVOS...\n";
echo "==========================\n";

foreach ($files as $file) {
    $filePath = $localPath . '/' . $file;
    if (file_exists($filePath)) {
        echo "✅ {$file} - OK\n";
    } else {
        echo "❌ {$file} - AUSENTE\n";
    }
}

echo "\n📋 INSTRUÇÕES DE DEPLOY:\n";
echo "=========================\n";
echo "1. Acesse o servidor via FTP/SFTP\n";
echo "2. Navegue até o diretório raiz do site\n";
echo "3. Crie o diretório: {$serverPath}\n";
echo "4. Faça upload dos arquivos:\n";
echo "   - {$localPath}/index.php → {$serverPath}/index.php\n";
echo "   - {$localPath}/monitor.html → {$serverPath}/monitor.html\n";
echo "   - {$localPath}/.htaccess → {$serverPath}/.htaccess\n";
echo "5. Crie o diretório: {$serverPath}/data\n";
echo "6. Crie o diretório: {$serverPath}/../logs\n";
echo "7. Teste acessando: https://bpsegurosimediato.com.br{$serverPath}/monitor.html\n\n";

echo "🧪 TESTE APÓS DEPLOY:\n";
echo "=====================\n";
echo "1. Acesse: https://bpsegurosimediato.com.br{$serverPath}/monitor.html\n";
echo "2. Execute: php test_octadesk_simulator.php\n";
echo "3. Verifique logs em: {$serverPath}/../logs/\n\n";

echo "📊 CONFIGURAÇÕES DO SIMULADOR:\n";
echo "==============================\n";
echo "URL Base: https://bpsegurosimediato.com.br{$serverPath}\n";
echo "API Keys válidas:\n";
echo "  - dev_octadesk_key_12345\n";
echo "  - test_octadesk_key_67890\n";
echo "  - simulator_octadesk_key\n\n";

echo "🔗 ENDPOINTS DISPONÍVEIS:\n";
echo "==========================\n";
echo "POST /api/v1/contacts - Criar contato\n";
echo "GET  /api/v1/contacts - Listar contatos\n";
echo "POST /api/v1/conversations - Criar conversa\n";
echo "GET  /api/v1/conversations - Listar conversas\n";
echo "POST /api/v1/messages - Enviar mensagem\n";
echo "GET  /api/v1/health - Health check\n";
echo "GET  /api/v1/info - Informações do simulador\n\n";

echo "✅ DEPLOY PREPARADO!\n";
echo "====================\n";
echo "Todos os arquivos estão prontos para upload.\n";
echo "Execute o deploy manualmente seguindo as instruções acima.\n";
