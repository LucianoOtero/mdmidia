<?php
// Teste NÃO EXECUTAR automaticamente. Use manual quando aprovado.

$endpoint = 'https://www.mdmidia.com.br/add_collect_octa.php';

$tests = [
    [
        'label' => 'Caso 1 - Contato existente (409 esperado, seguir com envio)',
        'payload' => [
            'NAME' => 'LUCIANO RODRIGUES OTERO',
            'NUMBER' => '+55 11976687668',
            'EMAIL' => 'lrotero@gmail.com',
            'CPF' => '08554607848',
            'CEP' => '03317000',
            'PLACA' => 'FPG-8D63',
            'produto' => 'auto',
            'utm_campaign' => '',
            'utm_source' => '',
            'page_address' => 'https://www.segurosimediato.com.br/'
        ]
    ],
    [
        'label' => 'Caso 2 - Contato novo (201 esperado, criar e enviar)',
        'payload' => [
            'NAME' => 'Teste vNext',
            'NUMBER' => '+55 11970000001',
            'EMAIL' => 'vnext.teste+' . date('YmdHis') . '@example.com',
            'CPF' => '00000000191',
            'CEP' => '04000000',
            'PLACA' => 'AAA0A00',
            'produto' => 'auto',
            'utm_campaign' => '',
            'utm_source' => '',
            'page_address' => 'https://www.segurosimediato.com.br/'
        ]
    ]
];

foreach ($tests as $t) {
    echo "\n==== " . $t['label'] . " ====\n";
    $ch = curl_init($endpoint);
    // Enviar como form-urlencoded para evitar problemas de parsing e WAF
    $body = http_build_query($t['payload']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded'
        ],
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_TIMEOUT => 25,
        // Contornar erro de SSL local no Windows
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
    $head = is_string($resp) ? substr($resp, 0, 300) : '';
    echo "Body(head): " . $head . "\n";
}
