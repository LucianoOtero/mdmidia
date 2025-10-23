<?php
require_once 'class.php';

// Cliente FlyingDonkeys
$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('82d5f667f3a65a9a43341a0705be2b0c');

// Função findLeadByEmail (copiada do add_travelangels.php)
function findLeadByEmail($email, $client, $logs = []) {
    try {
        $response = $client->request('GET', 'Lead', [
            'where' => [
                ['emailAddress', '=', $email]
            ],
            'maxSize' => 1
        ]);
        
        if (isset($response['list']) && count($response['list']) > 0) {
            return $response['list'][0];
        }
        return null;
    } catch (Exception $e) {
        return null;
    }
}

// Lista de emails para verificar
$emails = [
    'ansantos339@gmail.com',
    'rhcordeiro6@gmail.com', 
    'felipeengracia@gmail.com',
    'ricardolenzicor@gmail.com',
    'zenilsonmercedespais@gmail.com',
    'lugonzaga.andrade@gmail.com',
    'silvati004@gmail.com',
    'jgljau@gmail.com',
    'paulosergiosilva4231@gmail.com',
    'bruno_mendes@outlook.com.br',
    'ge.lopes.rodrigues@gmail.com',
    'danielefonttes0211@gmail.com',
    'verdao.sailer@hotmail.com',
    'marciokaiodenis@yahoo.com.br',
    'doralicetb@yahoo.com.br',
    'junior_guardia@hotmail.com'
];

echo "=== VERIFICAÇÃO DE LEADS NO FLYINGDONKEYS ===\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n\n";

$results = [];

foreach ($emails as $email) {
    $lead = findLeadByEmail($email, $client);
    
    if ($lead) {
        $status = "✅ Lead encontrado (ID: {$lead['id']}) - Nome: {$lead['name']}";
        echo $status . "\n";
        $results[$email] = [
            'status' => 'encontrado',
            'id' => $lead['id'],
            'nome' => $lead['name'],
            'data_criacao' => isset($lead['createdAt']) ? $lead['createdAt'] : 'N/A'
        ];
    } else {
        $status = "❌ Lead NÃO encontrado";
        echo $status . "\n";
        $results[$email] = [
            'status' => 'nao_encontrado',
            'id' => null,
            'nome' => null,
            'data_criacao' => null
        ];
    }
}

echo "\n=== RESUMO ===\n";
$encontrados = array_filter($results, function($r) { return $r['status'] === 'encontrado'; });
$nao_encontrados = array_filter($results, function($r) { return $r['status'] === 'nao_encontrado'; });

echo "Total verificados: " . count($emails) . "\n";
echo "Leads encontrados: " . count($encontrados) . "\n";
echo "Leads não encontrados: " . count($nao_encontrados) . "\n";

// Salvar resultados em arquivo JSON para uso posterior
file_put_contents('resultados_verificacao_flyingdonkeys.json', json_encode($results, JSON_PRETTY_PRINT));
echo "\nResultados salvos em: resultados_verificacao_flyingdonkeys.json\n";
?>




