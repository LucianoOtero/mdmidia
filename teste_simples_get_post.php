<?php
// Teste simples para demonstrar GET e POST
// URL: https://mdmidia.com.br/teste_simples_get_post.php

$method = $_SERVER['REQUEST_METHOD'];
$logs = fopen("teste_simples_get_post.txt", "a");

function logWithTimestamp($logs, $message) {
    $timestamp = date('Y-m-d H:i:s');
    fwrite($logs, "[$timestamp] $message" . PHP_EOL);
}

logWithTimestamp($logs, "=== TESTE SIMPLES GET/POST ===");
logWithTimestamp($logs, "Método HTTP: " . $method);

if ($method === 'POST') {
    $json = file_get_contents('php://input');
    logWithTimestamp($logs, "Dados POST: " . ($json ?: 'VAZIO'));
} elseif ($method === 'GET') {
    $json = json_encode($_GET);
    logWithTimestamp($logs, "Dados GET: " . ($json ?: 'VAZIO'));
    logWithTimestamp($logs, "Query String: " . ($_SERVER['QUERY_STRING'] ?? 'VAZIA'));
} else {
    logWithTimestamp($logs, "Método não suportado: " . $method);
}

logWithTimestamp($logs, "=== FIM TESTE ===");
fclose($logs);

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Teste GET/POST concluído',
    'method' => $method,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
