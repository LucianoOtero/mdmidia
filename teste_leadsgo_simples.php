<?php
// Endpoint simplificado para testar formato do leadsgo.online
// URL: https://mdmidia.com.br/teste_leadsgo_simples.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$logFile = "teste_leadsgo_simples.txt";
$timestamp = date('Y-m-d H:i:s');

// Log simples
function logSimple($message) {
    global $logFile, $timestamp;
    $log = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $log, FILE_APPEND | LOCK_EX);
}

logSimple("=== TESTE SIMPLES LEADSGO ===");
logSimple("Método: " . $_SERVER['REQUEST_METHOD']);
logSimple("Headers: " . json_encode(getallheaders()));
logSimple("Raw Input: " . file_get_contents('php://input'));
logSimple("POST: " . json_encode($_POST));
logSimple("GET: " . json_encode($_GET));
logSimple("=== FIM TESTE ===");

// Resposta simples
http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'message' => 'Teste recebido',
    'timestamp' => $timestamp
]);
?>
