<?php
echo "=== VERIFICAÇÃO DO LEAD CRIADO ===\n\n";

require_once('class.php');

// Configuração do cliente EspoCRM
$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

echo "🔍 Verificando leads criados nas últimas 10 minutos...\n\n";

try {
    // Buscar leads criados nas últimas 10 minutos
    $date10MinutesAgo = date('Y-m-d H:i:s', strtotime('-10 minutes'));

    $leads = $client->request('GET', 'Lead', [
        'where' => [
            'createdAt' => ['>=' => $date10MinutesAgo]
        ],
        'orderBy' => 'createdAt',
        'order' => 'desc',
        'maxSize' => 5
    ]);

    if (isset($leads['list']) && count($leads['list']) > 0) {
        echo "📋 Leads encontrados: " . count($leads['list']) . "\n\n";

        foreach ($leads['list'] as $lead) {
            echo "👤 Lead: " . $lead['firstName'] . "\n";
            echo "   📅 Criado: " . $lead['createdAt'] . "\n";
            echo "   🏷️ Source: " . ($lead['source'] ?? 'N/A') . "\n";
            echo "   📧 Email: " . ($lead['emailAddress'] ?? 'N/A') . "\n";
            echo "   🆔 ID: " . $lead['id'] . "\n";

            // Verificar se tem oportunidade associada
            if (!empty($lead['createdOpportunityId'])) {
                echo "   ✅ COM oportunidade: " . $lead['createdOpportunityId'] . "\n";

                // Buscar detalhes da oportunidade
                try {
                    $opportunity = $client->request('GET', 'Opportunity/' . $lead['createdOpportunityId']);
                    echo "   🎯 Oportunidade: " . ($opportunity['name'] ?? 'N/A') . "\n";
                    echo "   💰 Valor: " . ($opportunity['amount'] ?? 'N/A') . "\n";
                } catch (Exception $e) {
                    echo "   ⚠️ Erro ao buscar oportunidade: " . $e->getMessage() . "\n";
                }
            } else {
                echo "   ❌ SEM oportunidade\n";
            }
            echo "\n";
        }

        // Verificar se algum lead tem oportunidade
        $leadsWithOpportunity = 0;
        $leadsWithoutOpportunity = 0;

        foreach ($leads['list'] as $lead) {
            if (!empty($lead['createdOpportunityId'])) {
                $leadsWithOpportunity++;
            } else {
                $leadsWithoutOpportunity++;
            }
        }

        echo "📊 RESUMO:\n";
        echo "   Total de leads: " . count($leads['list']) . "\n";
        echo "   Leads com oportunidade: $leadsWithOpportunity\n";
        echo "   Leads sem oportunidade: $leadsWithoutOpportunity\n\n";

        if ($leadsWithOpportunity > 0) {
            echo "✅ WORKFLOW FUNCIONANDO! Oportunidades sendo criadas.\n";
        } else {
            echo "❌ WORKFLOW NÃO FUNCIONANDO! Nenhuma oportunidade criada.\n";
        }
    } else {
        echo "❌ Nenhum lead encontrado nas últimas 10 minutos.\n";
        echo "🔍 Verifique se o endpoint add_travelangels.php está funcionando.\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar leads: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ VERIFICAÇÃO CONCLUÍDA!\n";
