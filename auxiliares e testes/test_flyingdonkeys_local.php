<?php
// Teste local do add_flyingdonkeys.php

echo "=== TESTE LOCAL ADD_FLYINGDONKEYS ===\n\n";

// Simular dados do webhook
$testData = [
    'nome' => 'Teste Local FlyingDonkeys V10',
    'email' => 'teste.local.flyingdonkeys.v10@teste.com',
    'telefone' => '11999887766',
    'cpf' => '111.222.333-44',
    'marca' => 'Honda',
    'placa' => 'ABC1234',
    'ano' => '2020',
    'cep' => '01234-567',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'endereco' => 'Rua Teste, 123',
    'gclid' => 'test_gclid_local'
];

echo "📤 Dados de teste:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

// Simular o JSON que seria enviado pelo webhook
$jsonData = json_encode($testData);
echo "📄 JSON simulado: $jsonData\n\n";

// Simular o arquivo de log local
$logs = fopen("test_logs_flyingdonkeys.txt", "a");

// Função para log com timestamp
function logWithTimestamp($logs, $message)
{
    $timestamp = date('Y-m-d H:i:s');
    fwrite($logs, "[$timestamp] $message" . PHP_EOL);
    echo "[$timestamp] $message\n";
}

logWithTimestamp($logs, "=== INÍCIO TESTE LOCAL ===");
logWithTimestamp($logs, "Recebido do Webflow: " . $jsonData);

$data = json_decode($jsonData, true);
logWithTimestamp($logs, "Transformado em PHP Object: " . implode(';', $data));

// Incluir a classe EspoApiClient
require_once('class.php');

// Cliente para FlyingDonkeys
$clientFlyingDonkeys = new EspoApiClient('https://flyingdonkeys.com.br');
$clientFlyingDonkeys->setApiKey('82d5f667f3a65a9a43341a0705be2b0c');

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

logWithTimestamp($logs, "Telefone recebido: " . $telefone);
logWithTimestamp($logs, "Nome: " . $name);
logWithTimestamp($logs, "Email: " . $email);
logWithTimestamp($logs, "Source: " . $source);

// Payload comum para FlyingDonkeys
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

$leadIdFlyingDonkeys = null;

// ===== PROCESSAMENTO FLYINGDONKEYS =====
logWithTimestamp($logs, "--- PROCESSANDO FLYINGDONKEYS ---");

// Tentar criar lead no FlyingDonkeys
try {
    echo "🔗 Tentando conectar com FlyingDonkeys...\n";
    $responseFlyingDonkeys = $clientFlyingDonkeys->request('POST', 'Lead', $payload);
    $leadIdFlyingDonkeys = $responseFlyingDonkeys['id'];
    logWithTimestamp($logs, "FlyingDonkeys - Lead criado com sucesso: " . $leadIdFlyingDonkeys);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
    logWithTimestamp($logs, "FlyingDonkeys - Exceção capturada: " . $errorMessage);
    logWithTimestamp($logs, "FlyingDonkeys - Código do erro: " . $errorCode);
    
    echo "❌ Erro capturado: " . $errorMessage . "\n";
    echo "❌ Código do erro: " . $errorCode . "\n";
    
    // Se erro 409 (duplicata) ou se a resposta contém dados do lead (EspoCRM retorna lead existente como "erro")
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
                    echo "❌ Erro: Lead duplicado mas não encontrado por email\n";
                    return;
                }
            } else {
                logWithTimestamp($logs, "FlyingDonkeys - Erro: Lead duplicado mas não encontrado por email");
                echo "❌ Erro: Lead duplicado mas não encontrado por email\n";
                return;
            }
        }
    } else {
        logWithTimestamp($logs, "FlyingDonkeys - Erro real na criação do lead: " . $errorMessage);
        echo "❌ Erro real na criação do lead: " . $errorMessage . "\n";
        return;
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
            'source' => $source,

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
            logWithTimestamp($logs, "FlyingDonkeys - Nova oportunidade criada com duplicate = yes: " . $responseOpportunity['id']);
        } else {
            logWithTimestamp($logs, "FlyingDonkeys - Erro real na criação da oportunidade: " . $errorMessage);
        }
    }
}

logWithTimestamp($logs, "=== FIM TESTE LOCAL ===");
logWithTimestamp($logs, "Terminou");
logWithTimestamp($logs, "---");
fclose($logs);

// Retorna resposta de sucesso
echo "\n🎉 RESULTADO FINAL:\n";
echo "Status: success\n";
echo "Message: Lead e oportunidade processados com sucesso no FlyingDonkeys\n";
echo "Lead ID: " . ($leadIdFlyingDonkeys ?: 'N/A') . "\n\n";

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
        return null;
    } catch (Exception $e) {
        logWithTimestamp($logs, "Erro ao buscar lead por email: " . $e->getMessage());
        return null;
    }
}

echo "✅ TESTE LOCAL CONCLUÍDO!\n";
?>
