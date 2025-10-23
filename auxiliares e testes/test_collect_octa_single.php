<?php
$endpoint = 'https://www.mdmidia.com.br/add_collect_octa.php';
$payload = [
    'NAME' => 'LUCIANO RODRIGUES OTERO',
    'NUMBER' => '+55 11916535000',
    'EMAIL' => 'luciano_otero@hotmail.com',
    'CPF' => '08554607848',
    'CEP' => '03317000',
    'PLACA' => 'FPG-8D63',
    'produto' => 'auto',
    'utm_campaign' => '',
    'utm_source' => '',
    'page_address' => 'https://www.segurosimediato.com.br/'
];

$ch = curl_init($endpoint);
$body = http_build_query($payload);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded'
    ],
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_TIMEOUT => 25,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

echo "HTTP: $code\n";
if ($err) {
    echo "cURL error: $err\n";
}
$head = is_string($resp) ? substr($resp, 0, 500) : '';
echo "Body(head): $head\n";

