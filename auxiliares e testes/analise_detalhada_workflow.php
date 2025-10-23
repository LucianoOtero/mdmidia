<?php
require_once('class.php');

echo "=== ANÁLISE DETALHADA DO WORKFLOW - FLYINGDONKEYS ===\n\n";

$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

echo "1. Verificando workflows disponíveis...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/Workflow');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $workflows = json_decode($response, true);

    if (isset($workflows['list']) && is_array($workflows['list'])) {
        echo "📋 Total de workflows: " . count($workflows['list']) . "\n\n";

        $leadWorkflows = [];

        foreach ($workflows['list'] as $workflow) {
            if (
                stripos($workflow['name'], 'lead') !== false ||
                stripos($workflow['name'], 'opportunity') !== false ||
                stripos($workflow['name'], 'Lead') !== false ||
                stripos($workflow['name'], 'Opportunity') !== false
            ) {
                $leadWorkflows[] = $workflow;
            }
        }

        if (!empty($leadWorkflows)) {
            echo "🎯 WORKFLOWS RELACIONADOS A LEAD/OPPORTUNITY:\n";
            foreach ($leadWorkflows as $workflow) {
                echo "   📋 Nome: " . $workflow['name'] . "\n";
                echo "   🔄 Status: " . ($workflow['isActive'] ? '✅ ATIVO' : '❌ INATIVO') . "\n";
                echo "   📅 Criado: " . ($workflow['createdAt'] ?? 'N/A') . "\n";
                echo "   🆔 ID: " . $workflow['id'] . "\n";
                echo "   📝 Descrição: " . ($workflow['description'] ?? 'Sem descrição') . "\n";

                // Verificar detalhes do workflow
                $workflowId = $workflow['id'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://flyingdonkeys.com.br/api/v1/Workflow/$workflowId");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                $workflowDetail = curl_exec($ch);
                $detailHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($detailHttpCode == 200) {
                    $workflowData = json_decode($workflowDetail, true);

                    echo "   📊 Detalhes:\n";
                    echo "      🎯 Entidade: " . ($workflowData['entityType'] ?? 'N/A') . "\n";
                    echo "      🔄 Evento: " . ($workflowData['type'] ?? 'N/A') . "\n";
                    echo "      📋 Condições: " . (isset($workflowData['conditions']) ? 'Configuradas' : 'Não configuradas') . "\n";
                    echo "      ⚡ Ações: " . (isset($workflowData['actions']) ? 'Configuradas' : 'Não configuradas') . "\n";

                    if (isset($workflowData['conditions']) && is_array($workflowData['conditions'])) {
                        echo "      📝 Número de condições: " . count($workflowData['conditions']) . "\n";
                    }

                    if (isset($workflowData['actions']) && is_array($workflowData['actions'])) {
                        echo "      ⚡ Número de ações: " . count($workflowData['actions']) . "\n";

                        foreach ($workflowData['actions'] as $action) {
                            echo "         🔧 Ação: " . ($action['type'] ?? 'N/A') . "\n";
                            if (isset($action['targetEntityType'])) {
                                echo "            🎯 Entidade alvo: " . $action['targetEntityType'] . "\n";
                            }
                        }
                    }
                }

                echo "\n";
            }
        } else {
            echo "⚠️ NENHUM WORKFLOW LEAD/OPPORTUNITY ENCONTRADO!\n\n";
        }
    } else {
        echo "❌ Não foi possível obter workflows\n";
    }
} else {
    echo "❌ Erro ao buscar workflows - HTTP: $httpCode\n";
}

echo "2. Testando criação de lead para verificar execução do workflow...\n";

// Criar um lead de teste para verificar se o workflow executa
$testLeadData = [
    'firstName' => 'Teste Workflow ' . date('H:i:s'),
    'emailAddress' => 'teste.workflow@teste.com',
    'cCelular' => '11999999999',
    'addressPostalCode' => '00000-000',
    'source' => 'Teste',
    'status' => 'New'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/Lead');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testLeadData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Api-Key: 7a6c08d438ee131971f561fd836b5e15',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$testResponse = curl_exec($ch);
$testHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($testHttpCode == 200) {
    $testLead = json_decode($testResponse, true);
    echo "✅ Lead de teste criado com sucesso!\n";
    echo "   🆔 ID: " . $testLead['id'] . "\n";
    echo "   📝 Nome: " . $testLead['firstName'] . "\n";
    echo "   📅 Criado: " . $testLead['createdAt'] . "\n";

    // Aguardar um pouco e verificar se a oportunidade foi criada
    echo "\n   ⏳ Aguardando execução do workflow...\n";
    sleep(3);

    // Verificar o lead novamente
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/Lead/' . $testLead['id']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $updatedLead = curl_exec($ch);
    $updatedHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($updatedHttpCode == 200) {
        $leadData = json_decode($updatedLead, true);

        if (!empty($leadData['createdOpportunityId'])) {
            echo "   ✅ WORKFLOW EXECUTOU! Oportunidade criada: " . $leadData['createdOpportunityId'] . "\n";
        } else {
            echo "   ❌ WORKFLOW NÃO EXECUTOU! Nenhuma oportunidade criada.\n";
        }
    }
} else {
    echo "❌ Erro ao criar lead de teste - HTTP: $testHttpCode\n";
    echo "Resposta: " . substr($testResponse, 0, 200) . "...\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 CONCLUSÃO DA ANÁLISE:\n";
echo str_repeat("=", 60) . "\n";

echo "\n💡 DIAGNÓSTICO:\n";
echo "1. Se workflow está ativo mas não executa → Problema nas condições\n";
echo "2. Se workflow executa mas não cria oportunidade → Problema nas ações\n";
echo "3. Se não há workflow → Precisa ser criado\n";
echo "4. Se há erro na criação do lead → Problema de permissões\n";

echo "\n📋 PRÓXIMOS PASSOS:\n";
echo "1. Verificar configuração do workflow no EspoCRM\n";
echo "2. Testar condições manualmente\n";
echo "3. Verificar ações do workflow\n";
echo "4. Confirmar permissões de criação de oportunidade\n";

echo "\n✅ ANÁLISE CONCLUÍDA!\n";
