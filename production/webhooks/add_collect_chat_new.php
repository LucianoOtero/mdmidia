<?php
require_once('class.php');

// Função para log com timestamp
function logWithTimestamp($logs, $message)
{
    $timestamp = date('Y-m-d H:i:s');
    fwrite($logs, "[$timestamp] $message" . PHP_EOL);
}

// Função para buscar lead por email
function findLeadByEmail($email, $client, $logs)
{
    try {
        $leads = $client->request('GET', 'Lead', [
            'where' => [
                'emailAddress' => $email
            ],
            'maxSize' => 1
        ]);

        if (isset($leads['list']) && count($leads['list']) > 0) {
            logWithTimestamp($logs, "Lead encontrado por email: " . $leads['list'][0]['id']);
            return $leads['list'][0];
        }
        logWithTimestamp($logs, "Nenhum lead encontrado para o email: " . $email);
        return null;
    } catch (Exception $e) {
        logWithTimestamp($logs, "Erro ao buscar lead por email: " . $e->getMessage());
        return null;
    }
}

$json = file_get_contents('php://input');
$logs = fopen("collect_chat_logs.txt", "a");
logWithTimestamp($logs, "=== INÍCIO PROCESSAMENTO COLLECT CHAT V11 ===");
logWithTimestamp($logs, "Recebido do Collect Chat: " . $json);

$data = json_decode($json, true);
logWithTimestamp($logs, "Transformado em PHP Object: " . implode(';', $data));

$client = new EspoApiClient('https://travelangels.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

// Cliente para FlyingDonkeys (V7 completa)
$clientFlyingDonkeys = new EspoApiClient('https://flyingdonkeys.com.br');
$clientFlyingDonkeys->setApiKey('82d5f667f3a65a9a43341a0705be2b0c');

// Mapeamento dos campos recebidos do Collect Chat
$name = $data['NAME'] ?? '';
$telefone = $data['NUMBER'] ?? '';
$email = $data['EMAIL'] ?? '';
$cep = $data['CEP'] ?? '';
$cpf = $data['CPF'] ?? '';
$placa = $data['PLACA'] ?? '';
$gclid = $data['gclid'] ?? '';

// Campos não fornecidos pelo Collect Chat - valores padrão
$marca = '';      // Não fornecido pelo Collect Chat
$ano = '';        // Não fornecido pelo Collect Chat
$endereco = '';   // Não fornecido pelo Collect Chat
$cidade = '';     // Não fornecido pelo Collect Chat
$estado = '';     // Não fornecido pelo Collect Chat
$veiculo = '';    // Não fornecido pelo Collect Chat

// Configurações específicas para Collect Chat
$webpage = 'collect.chat';
$source = 'Collect Chat';

logWithTimestamp($logs, "=== DADOS RECEBIDOS DO COLLECT CHAT ===");
logWithTimestamp($logs, "Nome: " . $name);
logWithTimestamp($logs, "Telefone: " . $telefone);
logWithTimestamp($logs, "Email: " . $email);
logWithTimestamp($logs, "CPF: " . $cpf);
logWithTimestamp($logs, "Placa: " . $placa);
logWithTimestamp($logs, "CEP: " . $cep);
logWithTimestamp($logs, "GCLID: " . $gclid);
logWithTimestamp($logs, "Source: " . $source);
logWithTimestamp($logs, "Webpage: " . $webpage);

// Payload comum para ambos os sistemas (baseado no add_travelangels.php corrigido)
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

$leadIdTravelAngels = null;
$leadIdFlyingDonkeys = null;

// ===== PROCESSAMENTO TRAVELANGELS (MANTIDO COMO ESTÁ) =====
logWithTimestamp($logs, "--- PROCESSANDO TRAVELANGELS ---");

// Envio para TravelAngels (lógica original mantida)
try {
    $responseTravelAngels = $client->request('POST', 'Lead', $payload);
    $leadIdTravelAngels = $responseTravelAngels['id'];
    logWithTimestamp($logs, "TravelAngels - Lead criado com sucesso: " . $leadIdTravelAngels);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    logWithTimestamp($logs, "TravelAngels - Erro: " . $errorMessage);
}

// ===== PROCESSAMENTO FLYINGDONKEYS (LÓGICA V7 COMPLETA) =====
logWithTimestamp($logs, "--- PROCESSANDO FLYINGDONKEYS V7 ---");

// Tentar criar lead no FlyingDonkeys
try {
    $responseFlyingDonkeys = $clientFlyingDonkeys->request('POST', 'Lead', $payload);
    $leadIdFlyingDonkeys = $responseFlyingDonkeys['id'];
    logWithTimestamp($logs, "FlyingDonkeys - Lead criado com sucesso: " . $leadIdFlyingDonkeys);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    logWithTimestamp($logs, "FlyingDonkeys - Exceção capturada: " . $errorMessage);

    // Tratamento de lead duplicado
    if (
        strpos($errorMessage, '409') !== false || strpos($errorMessage, 'duplicate') !== false ||
        (strpos($errorMessage, '"id":') !== false && strpos($errorMessage, '"name":') !== false)
    ) {

        logWithTimestamp($logs, "FlyingDonkeys - Lead duplicado detectado - buscando por email: " . $email);

        $existingLead = findLeadByEmail($email, $clientFlyingDonkeys, $logs);
        if ($existingLead) {
            logWithTimestamp($logs, "FlyingDonkeys - Lead encontrado - atualizando: " . $existingLead['id']);

            // Atualizar lead existente
            $updateResponse = $clientFlyingDonkeys->request('PATCH', 'Lead/' . $existingLead['id'], $payload);
            logWithTimestamp($logs, "FlyingDonkeys - Lead atualizado com sucesso");
            $leadIdFlyingDonkeys = $existingLead['id'];
        } else {
            // Se não encontrou por email, mas a resposta contém dados do lead, usar esses dados
            if (strpos($errorMessage, '"id":') !== false && strpos($errorMessage, '"name":') !== false) {
                $leadData = json_decode($errorMessage, true);
                if (isset($leadData[0]['id'])) {
                    logWithTimestamp($logs, "FlyingDonkeys - Usando lead existente da resposta: " . $leadData[0]['id']);
                    $leadIdFlyingDonkeys = $leadData[0]['id'];
                } else {
                    logWithTimestamp($logs, "FlyingDonkeys - Erro: Lead duplicado mas não encontrado por email");
                    throw $e;
                }
            } else {
                logWithTimestamp($logs, "FlyingDonkeys - Erro: Lead duplicado mas não encontrado por email");
                throw $e;
            }
        }
    } else {
        logWithTimestamp($logs, "FlyingDonkeys - Erro real na criação do lead: " . $errorMessage);
        throw $e;
    }
}

// Tentar criar oportunidade no FlyingDonkeys
if ($leadIdFlyingDonkeys) {
    try {
        $opportunityPayload = [
            'name' => $name,
            'leadId' => $leadIdFlyingDonkeys,
            'stage' => 'Novo Sem Contato',
            'amount' => 0,
            'probability' => 10,

            // Campos do lead mapeados para oportunidade
            'cAnoFab' => $ano,
            'cAnoMod' => $ano,
            'cCEP' => $cep,
            'cCelular' => $telefone,
            'cCpftext' => $cpf,
            'cGclid' => $gclid,
            'cMarca' => $marca,
            'cPlaca' => $placa,
            'cWebpage' => $webpage,
            'cEmail' => $email,
            'cEmailAdress' => $email,
            'leadSource' => $source,

            // Campos adicionais do workflow
            'cSegpref' => isset($data['seguradora_preferencia']) ? $data['seguradora_preferencia'] : '',
            'cValorpret' => isset($data['valor_preferencia']) ? $data['valor_preferencia'] : '',
            'cModalidade' => isset($data['modalidade_seguro']) ? $data['modalidade_seguro'] : '',
            'cSegant' => isset($data['seguradora_apolice']) ? $data['seguradora_apolice'] : '',
            'cCiapol' => isset($data['ci']) ? $data['ci'] : '',
        ];

        $responseOpportunity = $clientFlyingDonkeys->request('POST', 'Opportunity', $opportunityPayload);
        logWithTimestamp($logs, "FlyingDonkeys - Oportunidade criada com sucesso: " . $responseOpportunity['id']);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        logWithTimestamp($logs, "FlyingDonkeys - Exceção oportunidade: " . $errorMessage);

        // Se erro 409 (duplicata), criar nova oportunidade com duplicate = yes
        if (strpos($errorMessage, '409') !== false || strpos($errorMessage, 'duplicate') !== false) {
            logWithTimestamp($logs, "FlyingDonkeys - Oportunidade duplicada detectada - criando nova com duplicate = yes");

            $opportunityPayload['duplicate'] = 'yes';
            $responseOpportunity = $clientFlyingDonkeys->request('POST', 'Opportunity', $opportunityPayload);
            logWithTimestamp($logs, "FlyingDonkeys - Oportunidade criada com duplicate = yes: " . $responseOpportunity['id']);
        } else {
            logWithTimestamp($logs, "FlyingDonkeys - Erro real na criação da oportunidade: " . $errorMessage);
        }
    }
}

// Resposta final
$response = [
    'status' => 'success',
    'message' => 'Lead processado no TravelAngels e FlyingDonkeys com sucesso',
    'leadIdTravelAngels' => $leadIdTravelAngels,
    'leadIdFlyingDonkeys' => $leadIdFlyingDonkeys
];

logWithTimestamp($logs, "=== FIM PROCESSAMENTO COLLECT CHAT V11 ===");
logWithTimestamp($logs, "Terminou");
logWithTimestamp($logs, "---");

fclose($logs);

header('Content-Type: application/json');
echo json_encode($response);
?>
