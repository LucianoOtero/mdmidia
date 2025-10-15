<?php
echo "=== TESTE DE MAPEAMENTO SIMPLES ===\n\n";

// Simular exatamente o que o add_travelangels.php está recebendo
$testData = [
    'nome' => 'TESTE MAPEAMENTO SILVA',
    'email' => 'teste.mapeamento@email.com',
    'telefone' => '11999888777',
    'cep' => '01234-567',
    'endereco' => 'Rua Teste, 456',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'veiculo' => 'Toyota Corolla',
    'ano' => '2021',
    'placa' => 'XYZ-9876',
    'cpf' => '987.654.321-00',
    'marca' => 'Toyota',
    'gclid' => 'test_mapeamento_12345'
];

echo "📋 Dados enviados:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

// Simular o que o add_travelangels.php está fazendo
$json = json_encode($testData);
$data = json_decode($json, true);

echo "🔍 Mapeamento dos campos:\n";

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

echo "   Nome mapeado: '$name'\n";
echo "   Telefone mapeado: '$telefone'\n";
echo "   Email mapeado: '$email'\n";
echo "   CEP mapeado: '$cep'\n";
echo "   CPF mapeado: '$cpf'\n";
echo "   Marca mapeada: '$marca'\n";
echo "   Placa mapeada: '$placa'\n";
echo "   Ano mapeado: '$ano'\n";
echo "   GCLID mapeado: '$gclid'\n";
echo "   Endereço mapeado: '$endereco'\n";
echo "   Cidade mapeada: '$cidade'\n";
echo "   Estado mapeado: '$estado'\n";
echo "   Veículo mapeado: '$veiculo'\n";
echo "   Webpage: '$webpage'\n";
echo "   Source: '$source'\n\n";

// Payload que será enviado
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

echo "📦 Payload que será enviado para o EspoCRM:\n";
foreach ($payload as $key => $value) {
    echo "   $key: '$value'\n";
}
echo "\n";

// Verificar se algum campo está vazio
$emptyFields = [];
foreach ($payload as $key => $value) {
    if (empty($value)) {
        $emptyFields[] = $key;
    }
}

if (!empty($emptyFields)) {
    echo "⚠️ CAMPOS VAZIOS DETECTADOS:\n";
    foreach ($emptyFields as $field) {
        echo "   - $field\n";
    }
    echo "\n";
} else {
    echo "✅ TODOS OS CAMPOS PREENCHIDOS!\n\n";
}

echo "🎯 CONCLUSÃO:\n";
if (empty($name)) {
    echo "❌ PROBLEMA: Nome está vazio - lead será criado como 'N/A'\n";
} else {
    echo "✅ Nome está correto: '$name'\n";
}

if (empty($email)) {
    echo "❌ PROBLEMA: Email está vazio\n";
} else {
    echo "✅ Email está correto: '$email'\n";
}

echo "\n✅ TESTE DE MAPEAMENTO CONCLUÍDO!\n";
?>
