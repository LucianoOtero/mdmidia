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

// Mapeamento correto dos campos recebidos
$name = isset($data['nome']) ? $data['nome'] : '';
$telefone = isset($data['telefone']) ? $data['telefone'] : '';
$email = isset($data['email']) ? $data['email'] : '';
$cep = isset($data['cep']) ? $data['cep'] : '';
$cpf = isset($data['cpf']) ? $data['cpf'] : '';
$marca = isset($data['marca']) ? $data['marca'] : '';
$placa = isset($data['placa']) ? $data['placa'] : '';
$ano = isset($data['ano']) ? $data['ano'] : '';
$gclid = isset($data['gclid']) ? $data['gclid'] : '';
$endereco = isset($data['endereco']) ? $data['endereco'] : '';
$cidade = isset($data['cidade']) ? $data['cidade'] : '';
$estado = isset($data['estado']) ? $data['estado'] : '';
$veiculo = isset($data['veiculo']) ? $data['veiculo'] : '';
$webpage = 'mdmidia.com.br';
$source = 'Site';

fwrite($logs, "Telefone recebido: " . $telefone . PHP_EOL);
fwrite($logs, "Nome: " . $name . PHP_EOL);
fwrite($logs, "Source: " . $source . PHP_EOL);

// Payload comum para ambos os sistemas
$payload = [
    'firstName' => $name,
    'emailAddress' => $email,
    'cCelular' => $telefone,
    'addressPostalCode' => $cep,
    'addressCity' => $cidade,
    'addressState' => $estado,
    'addressCountry' => 'Brasil',
    'addressStreet' => $endereco,
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

// Retorna resposta de sucesso para o webhook
http_response_code(200);
echo json_encode(['status' => 'success', 'message' => 'Lead inserido com sucesso no TravelAngels e FlyingDonkeys']);
