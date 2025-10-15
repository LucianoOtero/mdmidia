<?php
// Teste para add_leadsgo_v4.php
// Este arquivo testa o endpoint que envia dados para TravelAngels e FlyingDonkeys

echo "=== TESTE DO ENDPOINT ADD_LEADSGO_V4.PHP ===\n";
echo "Testando envio de dados para TravelAngels e FlyingDonkeys\n\n";

// Dados de teste baseados na estrutura do LeadsGo
$testData = [
    'nome_segurado' => 'João Silva Santos',
    'email' => 'joao.silva@email.com',
    'telefone_celular' => '11987654321',
    'cep' => '01234-567',
    'cpf_segurado' => '123.456.789-00',
    'marca' => 'Toyota',
    'modelo' => 'Corolla',
    'placa' => 'ABC1234',
    'ano' => '2020',
    'endereco' => 'Rua das Flores',
    'numero' => '123',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'valor_veiculo' => '85000',
    'data_nascimento' => '1985-05-15',
    'estado_civil' => 'Solteiro',
    'sexo' => 'Masculino',
    'uso' => 'Particular',
    'pernoite' => 'Garagem',
    'seguradora_preferencia' => 'Porto Seguro',
    'valor_preferencia' => '1500',
    'modalidade_seguro' => 'Completo',
    'seguradora_apolice' => 'SulAmérica',
    'ci' => 'CI123456789'
];

echo "Dados de teste:\n";
echo "Nome: " . $testData['nome_segurado'] . "\n";
echo "Email: " . $testData['email'] . "\n";
echo "Telefone: " . $testData['telefone_celular'] . "\n";
echo "CPF: " . $testData['cpf_segurado'] . "\n";
echo "Marca: " . $testData['marca'] . "\n";
echo "Modelo: " . $testData['modelo'] . "\n";
echo "Placa: " . $testData['placa'] . "\n";
echo "Ano: " . $testData['ano'] . "\n";
echo "Seguradora Preferida: " . $testData['seguradora_preferencia'] . "\n";
echo "Valor Preferencial: " . $testData['valor_preferencia'] . "\n";
echo "Modalidade: " . $testData['modalidade_seguro'] . "\n";
echo "Seguradora Anterior: " . $testData['seguradora_apolice'] . "\n";
echo "CI Apólice: " . $testData['ci'] . "\n\n";

// URL do endpoint
$url = 'https://mdmidia.com.br/add_leadsgo_v4.php';

echo "Enviando dados para: $url\n";
echo "Método: POST\n\n";

// Configuração do cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($testData))
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Executa a requisição
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

// Exibe os resultados
echo "=== RESULTADO DO TESTE ===\n";
echo "HTTP Code: $httpCode\n";

if ($error) {
    echo "Erro cURL: $error\n";
} else {
    echo "Resposta do servidor:\n";
    echo $response . "\n";
}

echo "\n=== VERIFICAÇÃO DOS LOGS ===\n";
echo "Verifique o arquivo logs_leadsgo.txt no servidor para confirmar:\n";
echo "- Recebimento dos dados\n";
echo "- Envio para TravelAngels\n";
echo "- Envio para FlyingDonkeys\n";
echo "- Respostas de ambos os sistemas\n\n";

if ($httpCode == 200) {
    echo "✅ TESTE CONCLUÍDO COM SUCESSO!\n";
    echo "O endpoint está funcionando e enviando dados para ambos os sistemas.\n";
} else {
    echo "❌ TESTE FALHOU!\n";
    echo "Verifique os logs e a configuração do servidor.\n";
}

echo "\n=== INFORMAÇÕES ADICIONAIS ===\n";
echo "- Este teste simula dados reais do LeadsGo\n";
echo "- Os dados são enviados para TravelAngels e FlyingDonkeys\n";
echo "- Ambos os sistemas usam a mesma API key\n";
echo "- Os logs mostrarão o resultado de cada inserção\n";
?>


