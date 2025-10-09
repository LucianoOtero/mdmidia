<?php
// Versão de debug do endpoint add_leadsgo.php
// Para identificar problemas no servidor

// Ativa exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DEBUG ENDPOINT LEADSGO ===\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";

// Teste 1: Verificar se o arquivo class.php existe
echo "1. Verificando arquivo class.php...\n";
if (file_exists('class.php')) {
    echo "✅ class.php encontrado\n";
} else {
    echo "❌ class.php NÃO encontrado\n";
    exit;
}

// Teste 2: Tentar incluir o class.php
echo "\n2. Incluindo class.php...\n";
try {
    require_once('class.php');
    echo "✅ class.php incluído com sucesso\n";
} catch (Exception $e) {
    echo "❌ Erro ao incluir class.php: " . $e->getMessage() . "\n";
    exit;
}

// Teste 3: Verificar se a classe existe
echo "\n3. Verificando classe EspoApiClient...\n";
if (class_exists('EspoApiClient')) {
    echo "✅ Classe EspoApiClient encontrada\n";
} else {
    echo "❌ Classe EspoApiClient NÃO encontrada\n";
    exit;
}

// Teste 4: Testar criação do cliente
echo "\n4. Testando criação do cliente EspoCRM...\n";
try {
    $client = new EspoApiClient('https://travelangels.com.br');
    $client->setApiKey('7a6c08d438ee131971f561fd836b5e15');
    echo "✅ Cliente EspoCRM criado com sucesso\n";
} catch (Exception $e) {
    echo "❌ Erro ao criar cliente EspoCRM: " . $e->getMessage() . "\n";
    exit;
}

// Teste 5: Verificar dados recebidos
echo "\n5. Verificando dados recebidos...\n";
$json = file_get_contents('php://input');
echo "Dados recebidos: " . ($json ? $json : 'NENHUM DADO') . "\n";

if ($json) {
    $data = json_decode($json, true);
    if ($data) {
        echo "✅ JSON decodificado com sucesso\n";
        echo "Estrutura dos dados:\n";
        print_r($data);
    } else {
        echo "❌ Erro ao decodificar JSON\n";
    }
} else {
    echo "⚠️ Nenhum dado recebido (normal para teste GET)\n";
}

// Teste 6: Verificar permissões de escrita
echo "\n6. Verificando permissões de escrita...\n";
$logFile = "logs_leadsgo_debug.txt";
$testContent = "Teste de escrita - " . date('Y-m-d H:i:s') . "\n";

if (file_put_contents($logFile, $testContent)) {
    echo "✅ Permissão de escrita OK\n";
    unlink($logFile); // Remove o arquivo de teste
} else {
    echo "❌ Sem permissão de escrita\n";
}

echo "\n=== DEBUG CONCLUÍDO ===\n";
echo "Se todos os testes passaram, o problema pode estar na lógica específica do endpoint.\n";
?>

