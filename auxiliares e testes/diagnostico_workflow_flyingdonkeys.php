<?php

/**
 * Script de Diagnóstico do Workflow "Lead to Opportunity" - FlyingDonkeys
 * Este script apenas faz diagnóstico, não modifica nada
 */

require_once('class.php');

echo "=== DIAGNÓSTICO DO WORKFLOW 'LEAD TO OPPORTUNITY' - FLYINGDONKEYS ===\n\n";

// Cliente para FlyingDonkeys
$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

echo "🔍 1. VERIFICANDO CONECTIVIDADE COM FLYINGDONKEYS\n";
echo str_repeat("-", 50) . "\n";

try {
    $testResponse = $client->request('GET', 'App/user');
    echo "✅ Conexão com FlyingDonkeys: OK\n";
    echo "📊 Usuário atual: " . ($testResponse['userName'] ?? 'N/A') . "\n";
} catch (Exception $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🔍 2. VERIFICANDO WORKFLOWS ATIVOS\n";
echo str_repeat("-", 50) . "\n";

try {
    $workflows = $client->request('GET', 'Workflow');

    if (isset($workflows['list']) && is_array($workflows['list'])) {
        echo "📋 Total de workflows encontrados: " . count($workflows['list']) . "\n\n";

        $leadWorkflows = [];
        $activeWorkflows = 0;

        foreach ($workflows['list'] as $workflow) {
            if ($workflow['isActive']) {
                $activeWorkflows++;
            }

            // Procurar workflows relacionados a Lead
            if (
                stripos($workflow['name'], 'lead') !== false ||
                stripos($workflow['name'], 'opportunity') !== false ||
                stripos($workflow['name'], 'Lead') !== false ||
                stripos($workflow['name'], 'Opportunity') !== false
            ) {

                $leadWorkflows[] = $workflow;
            }
        }

        echo "📊 Workflows ativos: $activeWorkflows\n";
        echo "📊 Workflows relacionados a Lead/Opportunity: " . count($leadWorkflows) . "\n\n";

        if (!empty($leadWorkflows)) {
            echo "🎯 WORKFLOWS ENCONTRADOS:\n";
            foreach ($leadWorkflows as $workflow) {
                echo "   📋 Nome: " . $workflow['name'] . "\n";
                echo "   🔄 Status: " . ($workflow['isActive'] ? '✅ ATIVO' : '❌ INATIVO') . "\n";
                echo "   📅 Criado: " . ($workflow['createdAt'] ?? 'N/A') . "\n";
                echo "   🆔 ID: " . $workflow['id'] . "\n";
                echo "   📝 Descrição: " . ($workflow['description'] ?? 'Sem descrição') . "\n";
                echo "\n";
            }
        } else {
            echo "⚠️ Nenhum workflow relacionado a Lead/Opportunity encontrado!\n";
        }
    } else {
        echo "❌ Não foi possível obter a lista de workflows\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar workflows: " . $e->getMessage() . "\n";
}

echo "\n🔍 3. VERIFICANDO ESTRUTURA DA ENTIDADE LEAD\n";
echo str_repeat("-", 50) . "\n";

try {
    $leadMetadata = $client->request('GET', 'Metadata/entities/Lead');

    if (isset($leadMetadata['fields'])) {
        echo "📋 Campos disponíveis na entidade Lead:\n";

        $importantFields = ['firstName', 'emailAddress', 'source', 'status', 'createdOpportunityId'];

        foreach ($importantFields as $field) {
            if (isset($leadMetadata['fields'][$field])) {
                $fieldInfo = $leadMetadata['fields'][$field];
                echo "   ✅ $field: " . ($fieldInfo['type'] ?? 'N/A') . "\n";
            } else {
                echo "   ❌ $field: Campo não encontrado\n";
            }
        }

        // Verificar campos customizados
        $customFields = [];
        foreach ($leadMetadata['fields'] as $fieldName => $fieldInfo) {
            if (strpos($fieldName, 'c') === 0) { // Campos que começam com 'c'
                $customFields[] = $fieldName;
            }
        }

        echo "\n📋 Campos customizados encontrados: " . count($customFields) . "\n";
        foreach ($customFields as $field) {
            echo "   📝 $field\n";
        }
    } else {
        echo "❌ Não foi possível obter metadados da entidade Lead\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar metadados do Lead: " . $e->getMessage() . "\n";
}

echo "\n🔍 4. VERIFICANDO ESTRUTURA DA ENTIDADE OPPORTUNITY\n";
echo str_repeat("-", 50) . "\n";

try {
    $opportunityMetadata = $client->request('GET', 'Metadata/entities/Opportunity');

    if (isset($opportunityMetadata['fields'])) {
        echo "📋 Campos disponíveis na entidade Opportunity:\n";

        $importantFields = ['name', 'amount', 'stage', 'leadSource', 'accountId'];

        foreach ($importantFields as $field) {
            if (isset($opportunityMetadata['fields'][$field])) {
                $fieldInfo = $opportunityMetadata['fields'][$field];
                echo "   ✅ $field: " . ($fieldInfo['type'] ?? 'N/A') . "\n";
            } else {
                echo "   ❌ $field: Campo não encontrado\n";
            }
        }
    } else {
        echo "❌ Não foi possível obter metadados da entidade Opportunity\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar metadados da Opportunity: " . $e->getMessage() . "\n";
}

echo "\n🔍 5. VERIFICANDO LEADS RECENTES SEM OPORTUNIDADES\n";
echo str_repeat("-", 50) . "\n";

try {
    // Buscar leads criados nas últimas 24 horas
    $yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));
    $leads = $client->request('GET', 'Lead?where[0][type]=after&where[0][attribute]=createdAt&where[0][value]=' . $yesterday);

    if (isset($leads['list']) && is_array($leads['list'])) {
        echo "📊 Leads criados nas últimas 24 horas: " . count($leads['list']) . "\n\n";

        $leadsWithoutOpportunity = 0;

        foreach ($leads['list'] as $lead) {
            if (empty($lead['createdOpportunityId'])) {
                $leadsWithoutOpportunity++;
                echo "   ❌ Lead sem oportunidade: {$lead['firstName']} (ID: {$lead['id']})\n";
                echo "      📅 Criado em: {$lead['createdAt']}\n";
                echo "      🏷️ Source: {$lead['source']}\n";
                echo "      📊 Status: {$lead['status']}\n\n";
            }
        }

        echo "📊 Total de leads sem oportunidade: $leadsWithoutOpportunity\n";
    } else {
        echo "❌ Não foi possível obter leads recentes\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar leads recentes: " . $e->getMessage() . "\n";
}

echo "\n🔍 6. VERIFICANDO LOGS DE WORKFLOW (SE DISPONÍVEL)\n";
echo str_repeat("-", 50) . "\n";

try {
    // Tentar buscar logs de workflow
    $workflowLogs = $client->request('GET', 'WorkflowLog?maxSize=10');

    if (isset($workflowLogs['list']) && is_array($workflowLogs['list'])) {
        echo "📋 Logs de workflow encontrados: " . count($workflowLogs['list']) . "\n\n";

        foreach ($workflowLogs['list'] as $log) {
            echo "   📝 Workflow: " . ($log['workflowName'] ?? 'N/A') . "\n";
            echo "   📅 Executado em: " . ($log['executedAt'] ?? 'N/A') . "\n";
            echo "   🔄 Status: " . ($log['status'] ?? 'N/A') . "\n";
            echo "   📊 Entidade: " . ($log['entityType'] ?? 'N/A') . "\n";
            echo "   🆔 ID da entidade: " . ($log['entityId'] ?? 'N/A') . "\n";

            if (isset($log['errorMessage']) && !empty($log['errorMessage'])) {
                echo "   ❌ Erro: " . $log['errorMessage'] . "\n";
            }
            echo "\n";
        }
    } else {
        echo "⚠️ Nenhum log de workflow encontrado ou logs não acessíveis\n";
    }
} catch (Exception $e) {
    echo "⚠️ Logs de workflow não acessíveis: " . $e->getMessage() . "\n";
}

echo "\n🔍 7. VERIFICANDO PERMISSÕES DO USUÁRIO API\n";
echo str_repeat("-", 50) . "\n";

try {
    $userInfo = $client->request('GET', 'App/user');

    if (isset($userInfo['id'])) {
        echo "👤 Usuário API: " . ($userInfo['userName'] ?? 'N/A') . "\n";
        echo "🆔 ID do usuário: " . $userInfo['id'] . "\n";
        echo "📊 Tipo: " . ($userInfo['type'] ?? 'N/A') . "\n";
        echo "✅ Ativo: " . ($userInfo['isActive'] ? 'Sim' : 'Não') . "\n";

        // Verificar permissões específicas
        if (isset($userInfo['permissions'])) {
            $permissions = $userInfo['permissions'];
            echo "\n📋 Permissões relevantes:\n";
            echo "   📝 Lead - Create: " . ($permissions['lead']['create'] ? '✅' : '❌') . "\n";
            echo "   📝 Lead - Edit: " . ($permissions['lead']['edit'] ? '✅' : '❌') . "\n";
            echo "   📝 Opportunity - Create: " . ($permissions['opportunity']['create'] ? '✅' : '❌') . "\n";
            echo "   📝 Opportunity - Edit: " . ($permissions['opportunity']['edit'] ? '✅' : '❌') . "\n";
        }
    } else {
        echo "❌ Não foi possível obter informações do usuário\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar informações do usuário: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 RESUMO DO DIAGNÓSTICO:\n";
echo str_repeat("=", 60) . "\n";

echo "\n💡 PRÓXIMOS PASSOS RECOMENDADOS:\n";
echo "1. Verifique se existe um workflow 'Lead to Opportunity' ativo\n";
echo "2. Confirme se as condições do workflow estão corretas\n";
echo "3. Verifique se o usuário API tem permissão para criar oportunidades\n";
echo "4. Teste criando um lead manualmente no EspoCRM\n";
echo "5. Verifique os logs de workflow para erros específicos\n";

echo "\n📋 PARA CORRIGIR O PROBLEMA:\n";
echo "1. Acesse: Administração → Workflows\n";
echo "2. Procure por workflows relacionados a Lead\n";
echo "3. Verifique se estão ativos e com condições corretas\n";
echo "4. Se necessário, crie um novo workflow 'Lead to Opportunity'\n";

echo "\n✅ DIAGNÓSTICO CONCLUÍDO!\n";
