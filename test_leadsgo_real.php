<?php
// Teste com dados reais do LeadsGo
// Simula exatamente a chamada que foi feita em 13:55:16

$url = 'https://mdmidia.com.br/add_leadsgo.php';

// Dados reais extraídos do log do servidor
$dadosReais = [
    "type" => "veiculos",
    "client_id" => "2301",
    "id_cotacao" => "55087",
    "ref_ext_user" => "2830",
    "data_captura" => "07/10/2025",
    "tipo_veiculo" => "CARRO",
    "modalidade_seguro" => "RENOVACAO - BONUS 1",
    "posse" => "SIM",
    "seguradora_preferencia" => "",
    "valor_preferencia" => "",
    "nome_segurado" => "FRANTHESKA LAIS VAN TIENEN",
    "nome_social" => "",
    "cpf_segurado" => "04442043930",
    "data_nascimento" => "13/06/1990",
    "estado_civil" => "SOLTEIRO(A)",
    "sexo" => "MASCULINO",
    "posse_veiculo" => "SIM",
    "email" => "FRANTHESKA.TIENEN@VP.ADV.BR",
    "origem" => "SEGUROOU",
    "telefone" => "41996395191",
    "endereco" => "AVENIDA SAO CASEMIRO",
    "numero" => "1150",
    "complemento" => "NAO INFORMADO",
    "cidade" => "ARAUCARIA",
    "estado" => "PR",
    "cep" => "83701990",
    "tipo_residencia" => "CASA",
    "marca" => "HYUNDAI",
    "modelo" => "HB20 LIMITED 1.0 FLEX 12V MEC.",
    "ano" => "2026",
    "placa" => "TBY6H86",
    "valor_veiculo" => "83796.00",
    "ref_ext" => "0152137",
    "tipo_segurado" => "O SEGURADO",
    "seguradora_atual" => "AZUL SEGUROS",
    "numero_apolice" => "00000000000000",
    "sinistro_ultimos_12_meses" => "NAO",
    "sinistro_ultimos_24_meses" => "NAO",
    "sinistro_ultimos_36_meses" => "NAO",
    "sinistro_ultimos_48_meses" => "NAO",
    "sinistro_ultimos_60_meses" => "NAO",
    "sinistro_ultimos_72_meses" => "NAO",
    "sinistro_ultimos_84_meses" => "NAO",
    "isento_imposto" => "NAO",
    "jovem_condutor" => "NAO",
    "uso" => "LAZER (PARTICULAR)",
    "pernoite" => "GARAGEM COM PORTAO"
];

echo "=== TESTE COM DADOS REAIS DO LEADSGO ===\n";
echo "URL: $url\n";
echo "Dados enviados:\n";
echo json_encode($dadosReais, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dadosReais));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Content-Length: ' . strlen(json_encode($dadosReais))
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

echo "Enviando requisição...\n\n";

// Executar requisição
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "=== RESULTADO ===\n";
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

if ($error) {
    echo "cURL Error: $error\n";
}

if ($httpCode === 200) {
    echo "\n✅ SUCESSO: Lead enviado com sucesso!\n";
    echo "Verifique o EspoCRM para confirmar se os dados estão formatados corretamente.\n";
} else {
    echo "\n❌ ERRO: Falha na requisição (HTTP $httpCode)\n";
}

echo "\n=== LOGS ESPERADOS ===\n";
echo "Verifique o arquivo logs_leadsgo.txt no servidor para ver:\n";
echo "- Se o nome aparece como 'FRANTHESKA LAIS VAN TIENEN'\n";
echo "- Se o email aparece como 'FRANTHESKA.TIENEN@VP.ADV.BR'\n";
echo "- Se o telefone aparece como '41996395191'\n";
echo "- Se o CPF aparece como '04442043930'\n";
echo "- Se a marca aparece como 'HYUNDAI'\n";
echo "- Se o modelo aparece como 'HB20 LIMITED 1.0 FLEX 12V MEC.'\n";
echo "- Se a placa aparece como 'TBY6H86'\n";
echo "- Se o ano aparece como '2026'\n";
?>
