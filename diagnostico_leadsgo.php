<?php
// Endpoint de diagnóstico para leadsgo.online
// URL: https://mdmidia.com.br/diagnostico_leadsgo.php

// Ativa exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log detalhado
$logFile = "diagnostico_leadsgo.txt";
$timestamp = date('Y-m-d H:i:s');

// Função para log
function logMessage($message) {
    global $logFile, $timestamp;
    $log = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $log, FILE_APPEND | LOCK_EX);
}

logMessage("=== INÍCIO DO DIAGNÓSTICO ===");

// 1. Verificar método HTTP
$method = $_SERVER['REQUEST_METHOD'];
logMessage("Método HTTP: $method");

// 2. Verificar headers
logMessage("Headers recebidos:");
foreach (getallheaders() as $name => $value) {
    logMessage("  $name: $value");
}

// 3. Verificar dados recebidos
$rawInput = file_get_contents('php://input');
logMessage("Dados brutos recebidos: " . ($rawInput ?: 'VAZIO'));

// 4. Verificar POST data
if (!empty($_POST)) {
    logMessage("Dados POST: " . json_encode($_POST));
} else {
    logMessage("Nenhum dado POST");
}

// 5. Verificar GET data
if (!empty($_GET)) {
    logMessage("Dados GET: " . json_encode($_GET));
} else {
    logMessage("Nenhum dado GET");
}

// 6. Tentar decodificar JSON
if ($rawInput) {
    $jsonData = json_decode($rawInput, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        logMessage("JSON válido decodificado");
        logMessage("Estrutura JSON: " . json_encode($jsonData, JSON_PRETTY_PRINT));
    } else {
        logMessage("ERRO JSON: " . json_last_error_msg());
    }
}

// 7. Verificar arquivo class.php
logMessage("Verificando class.php...");
if (file_exists('class.php')) {
    logMessage("✅ class.php encontrado");
    try {
        require_once('class.php');
        logMessage("✅ class.php incluído");
        
        if (class_exists('EspoApiClient')) {
            logMessage("✅ Classe EspoApiClient encontrada");
        } else {
            logMessage("❌ Classe EspoApiClient não encontrada");
        }
    } catch (Exception $e) {
        logMessage("❌ Erro ao incluir class.php: " . $e->getMessage());
    }
} else {
    logMessage("❌ class.php não encontrado");
}

// 8. Testar criação do cliente EspoCRM
logMessage("Testando cliente EspoCRM...");
try {
    $client = new EspoApiClient('https://travelangels.com.br');
    $client->setApiKey('7a6c08d438ee131971f561fd836b5e15');
    logMessage("✅ Cliente EspoCRM criado");
} catch (Exception $e) {
    logMessage("❌ Erro ao criar cliente EspoCRM: " . $e->getMessage());
}

// 9. Verificar permissões
logMessage("Verificando permissões...");
if (is_writable('.')) {
    logMessage("✅ Diretório atual tem permissão de escrita");
} else {
    logMessage("❌ Diretório atual SEM permissão de escrita");
}

// 10. Informações do servidor
logMessage("Informações do servidor:");
logMessage("  PHP Version: " . phpversion());
logMessage("  Server Software: " . $_SERVER['SERVER_SOFTWARE']);
logMessage("  Document Root: " . $_SERVER['DOCUMENT_ROOT']);
logMessage("  Script Filename: " . $_SERVER['SCRIPT_FILENAME']);

logMessage("=== FIM DO DIAGNÓSTICO ===");

// Resposta para o leadsgo
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Diagnóstico concluído',
    'timestamp' => $timestamp,
    'method' => $method,
    'data_received' => !empty($rawInput),
    'log_file' => $logFile
]);
?>
