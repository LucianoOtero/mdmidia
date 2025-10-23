<?php
// Teste do novo formato de log com timestamp
// URL: https://mdmidia.com.br/teste_timestamp.php

$method = $_SERVER['REQUEST_METHOD'];
$json = file_get_contents('php://input');
$logs = fopen("teste_timestamp.txt", "a");

// Função para log com timestamp
function logWithTimestamp($logs, $message) {
    $timestamp = date('Y-m-d H:i:s');
    fwrite($logs, "[$timestamp] $message" . PHP_EOL);
}

logWithTimestamp($logs, "=== TESTE DE TIMESTAMP ===");
logWithTimestamp($logs, "Método HTTP: " . $method);
logWithTimestamp($logs, "Dados recebidos: " . ($json ?: 'VAZIO'));
logWithTimestamp($logs, "Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'not_set'));
logWithTimestamp($logs, "=== FIM DO TESTE ===");

fclose($logs);

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Teste de timestamp concluído',
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $method
]);
?>
