<?php
// Teste para add_leadsgo_v2.php
// Simula dados do LeadsGo com os novos campos

$url = 'https://mdmidia.com.br/add_leadsgo_v2.php';

// Dados de teste baseados na documentação da API do LeadsGo
$testData = [
    "type" => "veiculos",
    "client_id" => "0000",
    "id_cotacao" => "0001",
    "ref_ext_user" => "1",
    "data_captura" => "09/08/2024",
    "tipo_veiculo" => "CARRO",
    "modalidade_seguro" => "SEGURO NOVO",
    "posse" => "SIM",
    "seguradora_preferencia" => "PORTO SEGURO",
    "valor_preferencia" => "4809.11",
    "nome_segurado" => "MARIA DOS SANTOS",
    "nome_social" => "",
    "cpf_segurado" => "66406344065",
    "data_nascimento" => "10/02/1980",
    "estado_civil" => "CASADO(A)",
    "sexo" => "FEMININO",
    "proprietario" => "SIM",
    "email" => "teste.v2@email.com",
    "origem" => "CORRETOU",
    "telefone_celular" => "11999999999",
    "logradouro" => "RUA DAS FLORES",
    "numero" => "123",
    "bairro" => "BAIRRO DAS FLORES",
    "cidade" => "HOLAMBRA",
    "uf" => "SP",
    "cep" => "13916434",
    "tipo_de_moradia" => "CASA",
    "marca" => "GM - CHEVROLET",
    "modelo" => "ONIX HATCH 1.0 12V Flex 5p Mec.",
    "ano" => "2023",
    "placa" => "FTP1234",
    "valor_fipe" => "49564.00",
    "cod_fipe" => "0045195",
    "condutor_principal" => "O SEGURADO",
    "seguradora_apolice" => "SEGURADORA ANTERIOR",
    "ci" => "00000000000000",
    "aplicativo" => "NAO",
    "kit_gas" => "NAO",
    "blindado" => "NAO",
    "financiado" => "NAO",
    "alarme" => "SIM",
    "isento_imposto" => "NAO",
    "jovem_condutor" => "NAO",
    "uso" => "LAZER (PARTICULAR)",
    "pernoite" => "GARAGEM COM PORTAO"
];

echo "=== TESTE ADD_LEADSGO_V2.PHP ===\n";
echo "URL: $url\n";
echo "Dados enviados:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Executar requisição
echo "Enviando requisição...\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

// Exibir resultados
echo "=== RESULTADO ===\n";
echo "HTTP Code: $httpCode\n";

if ($error) {
    echo "Erro cURL: $error\n";
} else {
    echo "Resposta:\n";
    echo $response . "\n";
}

// Verificar se foi sucesso
if ($httpCode == 200) {
    echo "\n✅ TESTE PASSOU - HTTP 200 OK\n";
} else {
    echo "\n❌ TESTE FALHOU - HTTP $httpCode\n";
}

echo "\n=== CAMPOS TESTADOS ===\n";
echo "✅ telefone_celular (novo mapeamento)\n";
echo "✅ seguradora_preferencia → cSegpref\n";
echo "✅ valor_preferencia → cValorpret\n";
echo "✅ modalidade_seguro → cModalidade\n";
echo "✅ seguradora_apolice → cSegant\n";
echo "✅ ci → cCiapol\n";
echo "✅ source = 'Baeta'\n";

echo "\n=== PRÓXIMOS PASSOS ===\n";
echo "1. Verificar logs em: https://mdmidia.com.br/logs_leadsgo.txt\n";
echo "2. Verificar lead criado no EspoCRM\n";
echo "3. Validar se todos os campos foram preenchidos corretamente\n";
?>
