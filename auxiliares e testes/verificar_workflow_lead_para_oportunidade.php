<?php
echo "=== VERIFICAÇÃO DO WORKFLOW 'LEAD PARA OPORTUNIDADE' ===\n\n";

require_once('class.php');

// Configuração do cliente EspoCRM
$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

echo "🔍 Verificando workflows no EspoCRM...\n\n";

try {
    // Buscar todos os workflows
    $workflows = $client->request('GET', 'Workflow');

    if (isset($workflows['list']) && count($workflows['list']) > 0) {
        echo "📋 Workflows encontrados: " . count($workflows['list']) . "\n\n";

        $foundLeadToOpportunity = false;

        foreach ($workflows['list'] as $workflow) {
            $name = $workflow['name'] ?? 'N/A';
            $isActive = $workflow['isActive'] ?? false;
            $entityType = $workflow['entityType'] ?? 'N/A';
            $type = $workflow['type'] ?? 'N/A';
            $id = $workflow['id'] ?? 'N/A';

            echo "🔧 Workflow: '$name'\n";
            echo "   🆔 ID: $id\n";
            echo "   📊 Entidade: $entityType\n";
            echo "   ⚡ Tipo: $type\n";
            echo "   ✅ Ativo: " . ($isActive ? 'Sim' : 'Não') . "\n";

            // Verificar se é o workflow "Lead para Oportunidade"
            if (
                strpos(strtolower($name), 'lead para oportunidade') !== false ||
                strpos(strtolower($name), 'lead to opportunity') !== false
            ) {
                $foundLeadToOpportunity = true;
                echo "   🎯 *** WORKFLOW ENCONTRADO! ***\n";

                // Verificar detalhes específicos
                try {
                    $workflowDetails = $client->request('GET', 'Workflow/' . $id);

                    echo "\n📋 DETALHES DO WORKFLOW:\n";
                    echo "   📝 Descrição: " . ($workflowDetails['description'] ?? 'N/A') . "\n";
                    echo "   🎯 Entidade: " . ($workflowDetails['entityType'] ?? 'N/A') . "\n";
                    echo "   ⚡ Tipo: " . ($workflowDetails['type'] ?? 'N/A') . "\n";
                    echo "   ✅ Ativo: " . (($workflowDetails['isActive'] ?? false) ? 'Sim' : 'Não') . "\n";

                    // Verificar condições
                    if (isset($workflowDetails['conditions'])) {
                        echo "   🔍 Condições: " . count($workflowDetails['conditions']) . " condição(ões)\n";
                    }

                    // Verificar ações
                    if (isset($workflowDetails['actions'])) {
                        echo "   ⚡ Ações: " . count($workflowDetails['actions']) . " ação(ões)\n";
                    }
                } catch (Exception $e) {
                    echo "   ⚠️ Erro ao buscar detalhes: " . $e->getMessage() . "\n";
                }
            }
            echo "\n";
        }

        if ($foundLeadToOpportunity) {
            echo "✅ SUCESSO! Workflow 'Lead para Oportunidade' encontrado!\n";
        } else {
            echo "❌ ERRO! Workflow 'Lead para Oportunidade' NÃO encontrado!\n";
            echo "🔍 Verifique se o nome está correto ou se foi criado.\n";
        }
    } else {
        echo "❌ Nenhum workflow encontrado.\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar workflows: " . $e->getMessage() . "\n";
    echo "🔍 Verifique se o usuário API tem permissões para acessar workflows.\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ VERIFICAÇÃO CONCLUÍDA!\n";
