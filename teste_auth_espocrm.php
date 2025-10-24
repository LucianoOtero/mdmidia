<?php
/**
 * CONSULTA ESPOCRM - TESTE DE AUTENTICAÇÃO
 * Verifica se conseguimos acessar o EspoCRM
 */

echo "=== TESTE DE AUTENTICAÇÃO ESPOCRM ===\n\n";

// Configurações do EspoCRM
$espocrm_url = 'https://dev.flyingdonkeys.com.br';
$api_key = 'd538e606685cecd0d76746906468eba4';

// Testar diferentes métodos de autenticação
echo "1. TESTANDO AUTENTICAÇÃO BÁSICA:\n";
$headers_basic = [
    'Authorization: Basic ' . base64_encode('api:' . $api_key),
    'Content-Type: application/json'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $espocrm_url . '/api/v1/App/user');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_basic);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $http_code . "\n";
if ($error) {
    echo "cURL Error: " . $error . "\n";
}
echo "Response: " . $response . "\n\n";

// Testar com header X-Api-Key
echo "2. TESTANDO COM X-Api-Key:\n";
$headers_api = [
    'X-Api-Key: ' . $api_key,
    'Content-Type: application/json'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $espocrm_url . '/api/v1/App/user');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $http_code . "\n";
if ($error) {
    echo "cURL Error: " . $error . "\n";
}
echo "Response: " . $response . "\n\n";

// Testar endpoint simples
echo "3. TESTANDO ENDPOINT SIMPLES:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $espocrm_url . '/api/v1/Lead?maxSize=1');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_basic);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $http_code . "\n";
if ($error) {
    echo "cURL Error: " . $error . "\n";
}
echo "Response: " . substr($response, 0, 500) . "...\n\n";

echo "=== FIM DO TESTE ===\n";
?>
