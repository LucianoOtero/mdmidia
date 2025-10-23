<?php
// Versão simplificada para teste de métodos HTTP
// URL: https://mdmidia.com.br/teste_metodos.php

$method = $_SERVER['REQUEST_METHOD'];
$json = file_get_contents('php://input');
$contentType = $_SERVER['CONTENT_TYPE'] ?? 'not_set';

// Log simples
$logFile = "teste_metodos.txt";
$timestamp = date('Y-m-d H:i:s');
$log = "[$timestamp] Método: $method | Content-Type: $contentType | Dados: " . ($json ?: 'VAZIO') . PHP_EOL;
file_put_contents($logFile, $log, FILE_APPEND);

// Respostas baseadas no método
if ($method === 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed. Use POST method.',
        'received_method' => $method,
        'expected_method' => 'POST'
    ]);
} elseif ($method === 'POST') {
    if (empty($json)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'No data received. Please send JSON data.',
            'method' => $method,
            'content_type' => $contentType
        ]);
    } else {
        $data = json_decode($json, true);
        if (!$data) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid JSON format',
                'json_error' => json_last_error_msg(),
                'received_data' => $json
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Data received and processed',
                'method' => $method,
                'content_type' => $contentType,
                'data_keys' => array_keys($data)
            ]);
        }
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed',
        'received_method' => $method,
        'allowed_methods' => ['POST']
    ]);
}
?>
