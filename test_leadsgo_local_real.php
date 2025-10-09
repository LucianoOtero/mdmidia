<?php
// Teste local com dados reais do LeadsGo
// Simula o processamento sem fazer requisição HTTP

require_once('class.php');

// Dados reais extraídos do log do servidor
$data = [
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

echo "=== TESTE LOCAL COM DADOS REAIS DO LEADSGO ===\n\n";

// Mapeamento dos dados do leadsgo.online
// Baseado na estrutura real dos dados recebidos
$name = isset($data['nome_segurado']) ? $data['nome_segurado'] : '';
$email = isset($data['email']) ? $data['email'] : '';
$telefone = isset($data['telefone']) ? $data['telefone'] : '';
$cep = isset($data['cep']) ? $data['cep'] : '';
$cpf = isset($data['cpf_segurado']) ? $data['cpf_segurado'] : '';
$marca = isset($data['marca']) ? $data['marca'] : '';
$placa = isset($data['placa']) ? $data['placa'] : '';
$ano = isset($data['ano']) ? $data['ano'] : '';
$modelo = isset($data['modelo']) ? $data['modelo'] : '';
$endereco = isset($data['endereco']) ? $data['endereco'] : '';
$numero = isset($data['numero']) ? $data['numero'] : '';
$cidade = isset($data['cidade']) ? $data['cidade'] : '';
$estado = isset($data['estado']) ? $data['estado'] : '';
$valorVeiculo = isset($data['valor_veiculo']) ? $data['valor_veiculo'] : '';
$dataNascimento = isset($data['data_nascimento']) ? $data['data_nascimento'] : '';
$estadoCivil = isset($data['estado_civil']) ? $data['estado_civil'] : '';
$sexo = isset($data['sexo']) ? $data['sexo'] : '';
$uso = isset($data['uso']) ? $data['uso'] : '';
$pernoite = isset($data['pernoite']) ? $data['pernoite'] : '';
$webpage = 'leadsgo.online';

echo "=== MAPEAMENTO DOS DADOS ===\n";
echo "Nome: '$name'\n";
echo "Email: '$email'\n";
echo "Telefone: '$telefone'\n";
echo "CPF: '$cpf'\n";
echo "Marca: '$marca'\n";
echo "Modelo: '$modelo'\n";
echo "Placa: '$placa'\n";
echo "Ano: '$ano'\n";
echo "CEP: '$cep'\n";
echo "Cidade: '$cidade'\n";
echo "Estado: '$estado'\n";
echo "Endereço: '$endereco, $numero'\n";
echo "Valor Veículo: '$valorVeiculo'\n";
echo "Data Nascimento: '$dataNascimento'\n";
echo "Estado Civil: '$estadoCivil'\n";
echo "Sexo: '$sexo'\n";
echo "Uso: '$uso'\n";
echo "Pernoite: '$pernoite'\n";
echo "Webpage: '$webpage'\n\n";

echo "=== DADOS QUE SERIAM ENVIADOS PARA O ESPOCRM ===\n";
$dadosEspoCRM = [
    'firstName' => $name,
    'emailAddress' => $email,
    'cCelular' => $telefone,
    'addressPostalCode' => $cep,
    'addressCity' => $cidade,
    'addressState' => $estado,
    'addressCountry' => 'Brasil',
    'addressStreet' => $endereco . ', ' . $numero,
    'cCpftext' => $cpf,
    'cMarca' => $marca,
    'cModelo' => $modelo,
    'cPlaca' => $placa,
    'cAnoMod' => $ano,
    'cValorVeiculo' => $valorVeiculo,
    'cDataNascimento' => $dataNascimento,
    'cEstadoCivil' => $estadoCivil,
    'cSexo' => $sexo,
    'cUso' => $uso,
    'cPernoite' => $pernoite,
    'cWebpage' => $webpage,
];

echo json_encode($dadosEspoCRM, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== VERIFICAÇÃO ===\n";
if (empty($name)) {
    echo "❌ ERRO: Nome está vazio!\n";
} else {
    echo "✅ Nome mapeado corretamente: '$name'\n";
}

if (empty($email)) {
    echo "❌ ERRO: Email está vazio!\n";
} else {
    echo "✅ Email mapeado corretamente: '$email'\n";
}

if (empty($telefone)) {
    echo "❌ ERRO: Telefone está vazio!\n";
} else {
    echo "✅ Telefone mapeado corretamente: '$telefone'\n";
}

if (empty($cpf)) {
    echo "❌ ERRO: CPF está vazio!\n";
} else {
    echo "✅ CPF mapeado corretamente: '$cpf'\n";
}

echo "\n=== PRÓXIMOS PASSOS ===\n";
echo "1. Se todos os campos estão corretos, execute: php test_leadsgo_real.php\n";
echo "2. Isso enviará os dados para o servidor real\n";
echo "3. Verifique o EspoCRM para confirmar a formatação\n";
echo "4. Verifique os logs do servidor para confirmar o processamento\n";
?>
