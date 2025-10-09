<?php
$url = "https://mdmidia.com.br/add_collect_chat.php";

$data = [
    "NAME" => "Luciano",
    "NUMBER" => "11999999999",
    "gclid" => "php-test-123"
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Erro ao enviar requisição.";
} else {
    echo "Resposta do servidor: " . $result;
}
?>
