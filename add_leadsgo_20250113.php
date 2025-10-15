<?php
require_once('class.php');

// Endpoint para receber webhooks do leadsgo.online
// URL: https://mdmidia.com.br/add_leadsgo.php
// Recebe os dados do webhook do leadsgo.online

$method = $_SERVER['REQUEST_METHOD'];
$logs = fopen("logs_leadsgo.txt", "a");

// Função para log com timestamp
function logWithTimestamp($logs, $message) {
    $timestamp = date('Y-m-d H:i:s');
    fwrite($logs, "[$timestamp] $message" . PHP_EOL);
}

// Log do método HTTP
logWithTimestamp($logs, "Método HTTP: " . $method);

// Aceitar GET e POST
if ($method === 'POST') {
    $json = file_get_contents('php://input');
    logWithTimestamp($logs, "Dados recebidos via POST (corpo da requisição)");
} elseif ($method === 'GET') {
    $json = json_encode($_GET);
    logWithTimestamp($logs, "Dados recebidos via GET (query string)");
    logWithTimestamp($logs, "Query string: " . $_SERVER['QUERY_STRING']);
} else {
    logWithTimestamp($logs, "ERRO: Método não suportado. Aceito apenas GET e POST, Recebido: " . $method);
    fclose($logs);
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 'error', 
        'message' => 'Method not allowed. Use GET or POST method.',
        'received_method' => $method,
        'expected_methods' => ['GET', 'POST']
    ]);
    exit;
}

// Verificar se há dados válidos antes de processar
if (empty($json)) {
    logWithTimestamp($logs, "ERRO: Tentativa de acesso sem dados - JSON vazio");
    fclose($logs);
    http_response_code(400); // Bad Request
    echo json_encode([
        'status' => 'error', 
        'message' => 'No data received. Please send JSON data.',
        'method' => $method,
        'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not_set'
    ]);
    exit;
}

// Verificar Content-Type
$contentType = $_SERVER['CONTENT_TYPE'] ?? 'not_set';
logWithTimestamp($logs, "Content-Type: " . $contentType);

// Verificar se Content-Type é application/json
if (strpos($contentType, 'application/json') === false) {
    logWithTimestamp($logs, "AVISO: Content-Type não é application/json");
}

logWithTimestamp($logs, "Recebido do LeadsGo: " . $json);

$data = json_decode($json, true);
if (!$data) {
    logWithTimestamp($logs, "ERRO: Falha ao decodificar JSON: " . json_last_error_msg());
    logWithTimestamp($logs, "JSON recebido: " . $json);
    fclose($logs);
    http_response_code(400);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Invalid JSON format',
        'json_error' => json_last_error_msg(),
        'received_data' => $json
    ]);
    exit;
}

logWithTimestamp($logs, "Transformado em PHP Object: " . implode(';' ,$data));

// Configuração do cliente EspoCRM
$client = new EspoApiClient('https://travelangels.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

// Mapeamento dos dados do leadsgo.online
// Baseado na estrutura real dos dados recebidos
$name = isset($data['nome_segurado']) ? $data['nome_segurado'] : '';
$email = isset($data['email']) ? $data['email'] : '';
$telefone = isset($data['telefone_celular']) ? $data['telefone_celular'] : '';
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
$source = 'Baeta';

// Novos campos do LeadsGo
$seguradoraPref = isset($data['seguradora_preferencia']) ? $data['seguradora_preferencia'] : '';
$valorPref = isset($data['valor_preferencia']) ? $data['valor_preferencia'] : '';
$modalidade = isset($data['modalidade_seguro']) ? $data['modalidade_seguro'] : '';
$seguradoraAnt = isset($data['seguradora_apolice']) ? $data['seguradora_apolice'] : '';
$ciApol = isset($data['ci']) ? $data['ci'] : '';

logWithTimestamp($logs, "Nome: " . $name);
logWithTimestamp($logs, "Email: " . $email);
logWithTimestamp($logs, "Telefone: " . $telefone);
logWithTimestamp($logs, "CPF: " . $cpf);
logWithTimestamp($logs, "Marca: " . $marca);
logWithTimestamp($logs, "Modelo: " . $modelo);
logWithTimestamp($logs, "Placa: " . $placa);
logWithTimestamp($logs, "Ano: " . $ano);
logWithTimestamp($logs, "Source: " . $source);
logWithTimestamp($logs, "Seguradora Preferida: " . $seguradoraPref);
logWithTimestamp($logs, "Valor Preferencial: " . $valorPref);
logWithTimestamp($logs, "Modalidade: " . $modalidade);
logWithTimestamp($logs, "Seguradora Anterior: " . $seguradoraAnt);
logWithTimestamp($logs, "CI Apólice: " . $ciApol);

// Envia os dados para o EspoCRM
// Usando apenas campos básicos que sabemos que existem
try {
    $response = $client->request('POST', 'Lead', [
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
        'cPlaca' => $placa,
        'cAnoMod' => $ano,
        'cWebpage' => $webpage,
        'source' => $source,
        'cSegpref' => $seguradoraPref,
        'cValorpret' => $valorPref,
        'cModalidade' => $modalidade,
        'cSegant' => $seguradoraAnt,
        'cCiapol' => $ciApol,
    ]);
    
    logWithTimestamp($logs, "Resposta: " . implode(',' ,$response));
} catch (Exception $e) {
    // Se a resposta do EspoCRM contém dados do lead, considera sucesso
    $errorMessage = $e->getMessage();
    logWithTimestamp($logs, "Exceção capturada: " . $errorMessage);
    
    // Verifica se a mensagem de erro contém dados de um lead criado
    if (strpos($errorMessage, '"id":') !== false && strpos($errorMessage, '"name":') !== false) {
        logWithTimestamp($logs, "Lead criado com sucesso (via exceção)");
        $response = json_decode($errorMessage, true);
    } else {
        logWithTimestamp($logs, "Erro real: " . $errorMessage);
        throw $e;
    }
}
logWithTimestamp($logs, "Terminou");
logWithTimestamp($logs, "---");
fclose($logs);

// Retorna resposta de sucesso para o webhook
http_response_code(200);
echo json_encode(['status' => 'success', 'message' => 'Lead inserido com sucesso']);
?>
