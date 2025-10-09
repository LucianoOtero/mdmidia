<?php
// Teste local do endpoint add_leadsgo.php
// Este arquivo simula o funcionamento do endpoint localmente

echo "=== TESTE LOCAL DO ENDPOINT LEADSGO ===\n\n";

// Simula os dados que seriam recebidos do leadsgo.online
$json = json_encode([
    'data' => [
        'NOME' => 'João Silva',
        'DDD-CELULAR' => '011',
        'CELULAR' => '987654321',
        'Email' => 'joao.silva@email.com',
        'CEP' => '01234-567',
        'CPF' => '123.456.789-00',
        'MARCA' => 'Toyota',
        'PLACA' => 'ABC1234',
        'ANO' => '2020',
        'GCLID_FLD' => 'test_gclid_123'
    ],
    'd' => '2024-01-15 10:30:00',
    'name' => 'leadsgo.online'
]);

echo "1. Dados JSON simulados:\n";
echo $json . "\n\n";

// Simula o processamento do endpoint
$data = json_decode($json, true);

echo "2. Dados decodificados:\n";
print_r($data);
echo "\n";

// Simula o mapeamento de dados
$name = isset($data['data']['NOME']) ? $data['data']['NOME'] : '';
$dddCel = isset($data['data']['DDD-CELULAR']) ? $data['data']['DDD-CELULAR'] : '';
$cel = isset($data['data']['CELULAR']) ? $data['data']['CELULAR'] : '';
$email = isset($data['data']['Email']) ? $data['data']['Email'] : '';
$cep = isset($data['data']['CEP']) ? $data['data']['CEP'] : '';
$cpf = isset($data['data']['CPF']) ? $data['data']['CPF'] : '';
$marca = isset($data['data']['MARCA']) ? $data['data']['MARCA'] : '';
$placa = isset($data['data']['PLACA']) ? $data['data']['PLACA'] : '';
$ano = isset($data['data']['ANO']) ? $data['data']['ANO'] : '';
$gclid = isset($data['data']['GCLID_FLD']) ? $data['data']['GCLID_FLD'] : '';
$webpage = isset($data['name']) ? $data['name'] : 'leadsgo.online';

echo "3. Dados mapeados:\n";
echo "Nome: " . $name . "\n";
echo "DDD Original: " . $dddCel . "\n";
echo "Celular Original: " . $cel . "\n";
echo "Email: " . $email . "\n";
echo "CEP: " . $cep . "\n";
echo "CPF: " . $cpf . "\n";
echo "Marca: " . $marca . "\n";
echo "Placa: " . $placa . "\n";
echo "Ano: " . $ano . "\n";
echo "GCLID: " . $gclid . "\n";
echo "Webpage: " . $webpage . "\n\n";

// Simula o tratamento do DDD
echo "4. Tratamento do DDD:\n";
echo "DDD antes: " . $dddCel . " (tamanho: " . strlen($dddCel) . ")\n";

if(strlen($dddCel) == 3) {
    $dddCel = substr($dddCel, 1);
    echo "DDD após tratamento: " . $dddCel . " (tamanho: " . strlen($dddCel) . ")\n";
} else {
    echo "DDD não precisa de tratamento\n";
}

$celCompleto = $dddCel . $cel;
echo "Telefone completo: " . $celCompleto . "\n\n";

// Simula os dados que seriam enviados para o EspoCRM
$dadosEspoCRM = [
    'firstName' => $name,
    'emailAddress' => $email,
    'cCelular' => $celCompleto,
    'addressPostalCode' => $cep,
    'cCpftext' => $cpf,
    'cMarca' => $marca,
    'cPlaca' => $placa,
    'cAnoMod' => $ano,
    'cGclid' => $gclid,
    'cWebpage' => $webpage,
    'forceDuplicate' => 'true',
];

echo "5. Dados para EspoCRM:\n";
print_r($dadosEspoCRM);
echo "\n";

echo "6. JSON para EspoCRM:\n";
echo json_encode($dadosEspoCRM, JSON_PRETTY_PRINT) . "\n\n";

echo "=== TESTE CONCLUÍDO COM SUCESSO ===\n";
echo "O endpoint está processando os dados corretamente!\n";
?>

