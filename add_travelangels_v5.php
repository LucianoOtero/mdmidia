<?php
require_once('class.php');
$json = file_get_contents('php://input');
$logs = fopen("logs_travelangels.txt", "a");
fwrite($logs, "Recebido do Webflow: " . $json . PHP_EOL);

$data = json_decode($json, true);
fwrite($logs, "Transformado em PHP Object: " . implode(';', $data) . PHP_EOL);

$client = new EspoApiClient('https://travelangels.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

// Cliente para FlyingDonkeys
$clientFlyingDonkeys = new EspoApiClient('https://flyingdonkeys.com.br');
$clientFlyingDonkeys->setApiKey('7a6c08d438ee131971f561fd836b5e15');

$name = $data['data']['NOME'];
$dddCel = $data['data']['DDD-CELULAR'];
$cel = $data['data']['CELULAR'];
$email = $data['data']['Email'];
$cep = $data['data']['CEP'];
$cpf = $data['data']['CPF'];
$marca = $data['data']['MARCA'];
$placa = $data['data']['PLACA'];
$ano = $data['data']['ANO'];
$gclid = $data['data']['GCLID_FLD'];
$date = $data['d'];
$webpage = $data['name'];
$source = 'Site';

fwrite($logs, "DDDs antes da checagem de digitos: " . $dddCel . ";" . $dddTel . PHP_EOL);

if (strlen($dddCel) == 3) {
    $dddCel = substr($dddCel, 1);
};

if (strlen($dddTel) == 3) {
    $dddTel = substr($dddTel, 1);
};
fwrite($logs, "DDDs apos da checagem de digitos: " . $dddCel . ";" . $dddTel . PHP_EOL);
$cel = $dddCel . $cel;
fwrite($logs, "Telefone apos colocar ddd: " . $cel . ";" . $tel . PHP_EOL);
fwrite($logs, "Nome: " . $name . PHP_EOL);
fwrite($logs, "Source: " . $source . PHP_EOL);

// Payload comum para ambos os sistemas
$payload = [
    'firstName' => $name,
    'emailAddress' => $email,
    'cCelular' => $cel,
    'addressPostalCode' => $cep,
    'cCpftext' => $cpf,
    'cMarca' => $marca,
    'cPlaca' => $placa,
    'cAnoMod' => $ano,
    'cGclid' => $gclid,
    'cWebpage' => $webpage,
    'source' => $source,
];

// Envio para TravelAngels
try {
    $responseTravelAngels = $client->request('POST', 'Lead', $payload);
    fwrite($logs, "TravelAngels - Resposta: " . implode(',', $responseTravelAngels) . PHP_EOL);
} catch (Exception $e) {
    fwrite($logs, "TravelAngels - Erro: " . $e->getMessage() . PHP_EOL);
}

// Envio para FlyingDonkeys
try {
    $responseFlyingDonkeys = $clientFlyingDonkeys->request('POST', 'Lead', $payload);
    fwrite($logs, "FlyingDonkeys - Resposta: " . implode(',', $responseFlyingDonkeys) . PHP_EOL);
} catch (Exception $e) {
    fwrite($logs, "FlyingDonkeys - Erro: " . $e->getMessage() . PHP_EOL);
}
fwrite($logs, "Terminou" . PHP_EOL . "---" . PHP_EOL);
fclose($logs);
