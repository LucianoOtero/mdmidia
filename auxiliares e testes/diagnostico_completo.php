<?php
require_once('class.php');

echo "=== DIAGNÓSTICO WORKFLOW FLYINGDONKEYS ===\n\n";

$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

echo "1. Testando conexão básica...\n";

// Teste mais simples primeiro
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/App/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $userData = json_decode($response, true);
    echo "✅ Conexão OK - Usuário: " . ($userData['userName'] ?? 'N/A') . "\n\n";

    echo "2. Verificando workflows...\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/Workflow');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $workflowResponse = curl_exec($ch);
    $workflowHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($workflowHttpCode == 200) {
        $workflows = json_decode($workflowResponse, true);
        echo "📋 Total de workflows: " . count($workflows['list']) . "\n";

        $leadWorkflows = [];
        $activeWorkflows = 0;

        foreach ($workflows['list'] as $wf) {
            if ($wf['isActive']) {
                $activeWorkflows++;
            }

            if (
                stripos($wf['name'], 'lead') !== false ||
                stripos($wf['name'], 'opportunity') !== false ||
                stripos($wf['name'], 'Lead') !== false ||
                stripos($wf['name'], 'Opportunity') !== false
            ) {
                $leadWorkflows[] = $wf;
            }
        }

        echo "📋 Workflows ativos: $activeWorkflows\n";
        echo "📋 Workflows Lead/Opportunity: " . count($leadWorkflows) . "\n\n";

        if (!empty($leadWorkflows)) {
            echo "🎯 WORKFLOWS ENCONTRADOS:\n";
            foreach ($leadWorkflows as $wf) {
                echo "   📋 " . $wf['name'] . " - " . ($wf['isActive'] ? '✅ ATIVO' : '❌ INATIVO') . "\n";
                echo "   📅 Criado: " . ($wf['createdAt'] ?? 'N/A') . "\n";
                echo "   🆔 ID: " . $wf['id'] . "\n\n";
            }
        } else {
            echo "⚠️ NENHUM WORKFLOW LEAD/OPPORTUNITY ENCONTRADO!\n\n";
        }
    } else {
        echo "❌ Erro ao buscar workflows - HTTP: $workflowHttpCode\n";
    }

    echo "3. Verificando leads recentes...\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/Lead?maxSize=10');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $leadsResponse = curl_exec($ch);
    $leadsHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($leadsHttpCode == 200) {
        $leads = json_decode($leadsResponse, true);
        echo "📋 Leads encontrados: " . count($leads['list']) . "\n\n";

        $leadsWithoutOpportunity = 0;

        foreach ($leads['list'] as $lead) {
            $hasOpp = !empty($lead['createdOpportunityId']);
            if (!$hasOpp) {
                $leadsWithoutOpportunity++;
            }

            echo ($hasOpp ? "✅" : "❌") . " " . $lead['firstName'] . " - " . ($hasOpp ? "COM" : "SEM") . " oportunidade\n";
            echo "   📅 Criado: " . $lead['createdAt'] . "\n";
            echo "   🏷️ Source: " . ($lead['source'] ?? 'N/A') . "\n";
            echo "   🆔 ID: " . $lead['id'] . "\n\n";
        }

        echo "📊 RESUMO:\n";
        echo "   Total de leads: " . count($leads['list']) . "\n";
        echo "   Leads sem oportunidade: $leadsWithoutOpportunity\n";
        echo "   Leads com oportunidade: " . (count($leads['list']) - $leadsWithoutOpportunity) . "\n";
    } else {
        echo "❌ Erro ao buscar leads - HTTP: $leadsHttpCode\n";
    }
} else {
    echo "❌ Erro de conexão - HTTP: $httpCode\n";
    echo "Resposta: " . substr($response, 0, 200) . "...\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 CONCLUSÕES:\n";
echo str_repeat("=", 60) . "\n";

echo "\n💡 PRÓXIMOS PASSOS:\n";
echo "1. Se não há workflows Lead/Opportunity → CRIAR UM\n";
echo "2. Se há workflows mas estão inativos → ATIVAR\n";
echo "3. Se há workflows ativos mas não funcionam → VERIFICAR CONDIÇÕES\n";
echo "4. Se há muitos leads sem oportunidade → WORKFLOW NÃO ESTÁ EXECUTANDO\n";

echo "\n📋 PARA CORRIGIR:\n";
echo "1. Acesse: https://flyingdonkeys.com.br\n";
echo "2. Vá para: Administração → Workflows\n";
echo "3. Procure por workflows relacionados a Lead\n";
echo "4. Verifique se estão ativos e com condições corretas\n";
echo "5. Se necessário, crie um novo workflow 'Lead to Opportunity'\n";

echo "\n✅ DIAGNÓSTICO CONCLUÍDO!\n";
