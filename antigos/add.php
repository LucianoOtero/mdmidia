<?php
require_once('class.php');
$json = file_get_contents('php://input');
$logs = fopen("logs.txt", "a");
fwrite($logs, "Recebido do Webflow: " . $json . PHP_EOL);

$data = json_decode($json, true);
fwrite($logs, "Transformado em PHP Object: " . implode(';' ,$data) . PHP_EOL);

$client = new EspoApiClient('https://mdmidia.com.br/espo');
$client->setApiKey('1c45a87278070df0e7442df622fb7ff7');

$name = $data['data']['NOME'];
$email = $data['data']['Email'];
$tel = $data['data']['TELEFONE'];
$cel = $data['data']['CELULAR'];
$cep = $data['data']['CEP'];
$cidade = $data['data']['CIDADE'];
$estado = $data['data']['ESTADO'];
$marca = $data['data']['MARCA'];
$veiculo = $data['data']['VEICULO'];
$modelo = $data['data']['MODELO'];
$ano = $data['data']['ANO'];
$gclid = $data['data']['GCLID_FLD'];
$sequencia = $data['data']['SEQUENCIA_FLD'];
$tipoVeiculo = $data['data']['Tipo Veiculo'];
$cobertura = $data['data']['Cobertura'];
$reboque = $data['data']['Reboque'];
$raca = $data['data']['raca'];
$nomePet = $data['data']['NOME DO PET'];
$date = $data['d'];
$webpage = $data['name'];
$dddCel = $data['data']['DDD-CELULAR'];
$dddTel = $data['data']['DDD-TELEFONE'];

fwrite($logs, "DDDs antes da checagem de digitos: " . $dddCel . ";" . $dddTel . PHP_EOL);

if(strlen($dddCel) == 3) {
    $dddCel = substr($dddCel, 1);
};

if(strlen($dddTel) == 3) {
    $dddTel = substr($dddTel, 1);
};
fwrite($logs, "DDDs apos da checagem de digitos: " . $dddCel . ";" . $dddTel . PHP_EOL);
$tel = $dddTel . $tel;
$cel = $dddCel . $cel;
fwrite($logs, "Telefone apos colocar ddd: " . $cel . ";" . $tel . PHP_EOL);
$response = $client->request('POST', 'Lead', [
    'name' => $name,
    'firstName' => $name,
    'emailAddress' => $email,
    'cTelefone' => $tel,
    'cCelular' => $cel,
    'addressPostalCode' => $cep,
    'addressCity' => $cidade,
    'addressState' => $estado,
    'addressCountry' => 'Brasil',
    'cMarca' => $marca,
    'cVeiculo' => $veiculo,
    'cModelo' => $modelo,
    'cAno' => $ano,
    'cGClid' => $gclid,
    'cTipoDeVeiculo' => $tipoVeiculo,
    'cCobertura' => $cobertura,
    'cReboque' => $reboque,
    'cRacaPet' => $raca,
    'cNomePet' => $nomePet,
    'cWebpage' => $webpage,
    'cSequencia' => $sequencia,
]);
fwrite($logs, "Resposta: " . implode(',' ,$response) . PHP_EOL);
fwrite($logs, "Terminou" . PHP_EOL . "---" . PHP_EOL);
fclose($logs);
?>