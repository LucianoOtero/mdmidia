<?php
require_once('class.php');

echo "=== DIAGNÓSTICO WORKFLOW FLYINGDONKEYS ===\n\n";

$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

echo "1. Testando conexão...\n";
try {
    $user = $client->request('GET', 'App/user');
    echo "✅ Conexão OK - Usuário: " . $user['userName'] . "\n\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    exit;
}

echo "2. Verificando workflows...\n";
try {
    $workflows = $client->request('GET', 'Workflow');
    echo "📋 Total de workflows: " . count($workflows['list']) . "\n";

    $leadWorkflows = [];
    foreach ($workflows['list'] as $wf) {
        if (stripos($wf['name'], 'lead') !== false || stripos($wf['name'], 'opportunity') !== false) {
            $leadWorkflows[] = $wf;
        }
    }

    echo "📋 Workflows Lead/Opportunity: " . count($leadWorkflows) . "\n\n";

    foreach ($leadWorkflows as $wf) {
        echo "🎯 " . $wf['name'] . " - " . ($wf['isActive'] ? 'ATIVO' : 'INATIVO') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n3. Verificando leads recentes...\n";
try {
    $leads = $client->request('GET', 'Lead?maxSize=5');
    echo "📋 Leads encontrados: " . count($leads['list']) . "\n";

    foreach ($leads['list'] as $lead) {
        $hasOpp = !empty($lead['createdOpportunityId']);
        echo ($hasOpp ? "✅" : "❌") . " " . $lead['firstName'] . " - " . ($hasOpp ? "COM" : "SEM") . " oportunidade\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n✅ Diagnóstico concluído!\n";
