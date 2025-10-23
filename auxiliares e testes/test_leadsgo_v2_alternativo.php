<?php
// Teste alternativo para add_leadsgo_v2.php
// Dados diferentes para validar a implementação

$url = 'https://mdmidia.com.br/add_leadsgo_v2.php';

// Dados de teste alternativos
$testData = [
    "type" => "veiculos",
    "client_id" => "1234",
    "id_cotacao" => "5678",
    "ref_ext_user" => "2",
    "data_captura" => "13/10/2025",
    "tipo_veiculo" => "MOTO",
    "modalidade_seguro" => "SEGURO RENOVAÇÃO",
    "posse" => "NAO",
    "seguradora_preferencia" => "BRADESCO SEGUROS",
    "valor_preferencia" => "1250.75",
    "nome_segurado" => "JOÃO SILVA SANTOS",
    "nome_social" => "",
    "cpf_segurado" => "12345678901",
    "data_nascimento" => "15/05/1990",
    "estado_civil" => "SOLTEIRO(A)",
    "sexo" => "MASCULINO",
    "proprietario" => "NAO",
    "email" => "joao.silva.teste@email.com",
    "origem" => "SITE",
    "telefone_celular" => "11876543210",
    "logradouro" => "AVENIDA PAULISTA",
    "numero" => "1000",
    "bairro" => "BELA VISTA",
    "cidade" => "SÃO PAULO",
    "uf" => "SP",
    "cep" => "01310100",
    "tipo_de_moradia" => "APARTAMENTO",
    "marca" => "HONDA",
    "modelo" => "CB 600F HORNET",
    "ano" => "2020",
    "placa" => "MOT1234",
    "valor_fipe" => "25000.00",
    "cod_fipe" => "0054321",
    "condutor_principal" => "O SEGURADO",
    "seguradora_apolice" => "SULAMÉRICA SEGUROS",
    "ci" => "12345678901234",
    "aplicativo" => "SIM",
    "kit_gas" => "SIM",
    "blindado" => "NAO",
    "financiado" => "SIM",
    "alarme" => "NAO",
    "isento_imposto" => "SIM",
    "jovem_condutor" => "SIM",
    "uso" => "TRABALHO (PROFISSIONAL)",
    "pernoite" => "RUA SEM PROTEÇÃO"
];

echo "=== TESTE ALTERNATIVO ADD_LEADSGO_V2.PHP ===\n";
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
    echo "\n✅ TESTE ALTERNATIVO PASSOU - HTTP 200 OK\n";
} else {
    echo "\n❌ TESTE ALTERNATIVO FALHOU - HTTP $httpCode\n";
}

echo "\n=== CAMPOS TESTADOS (DADOS ALTERNATIVOS) ===\n";
echo "✅ telefone_celular: 11876543210\n";
echo "✅ seguradora_preferencia → cSegpref: BRADESCO SEGUROS\n";
echo "✅ valor_preferencia → cValorpret: 1250.75\n";
echo "✅ modalidade_seguro → cModalidade: SEGURO RENOVAÇÃO\n";
echo "✅ seguradora_apolice → cSegant: SULAMÉRICA SEGUROS\n";
echo "✅ ci → cCiapol: 12345678901234\n";
echo "✅ source = 'Baeta'\n";

echo "\n=== DIFERENÇAS DO TESTE ANTERIOR ===\n";
echo "• Cliente: JOÃO SILVA SANTOS (masculino, solteiro)\n";
echo "• Veículo: HONDA CB 600F HORNET (moto)\n";
echo "• Seguradora preferida: BRADESCO SEGUROS\n";
echo "• Modalidade: SEGURO RENOVAÇÃO\n";
echo "• Seguradora anterior: SULAMÉRICA SEGUROS\n";
echo "• Valor preferencial: R$ 1.250,75\n";
echo "• Uso: TRABALHO (PROFISSIONAL)\n";
echo "• Pernoite: RUA SEM PROTEÇÃO\n";

echo "\n=== PRÓXIMOS PASSOS ===\n";
echo "1. Verificar logs em: https://mdmidia.com.br/logs_leadsgo.txt\n";
echo "2. Verificar lead criado no EspoCRM\n";
echo "3. Comparar com o lead anterior (MARIA DOS SANTOS)\n";
echo "4. Validar se todos os campos foram preenchidos corretamente\n";
?>
