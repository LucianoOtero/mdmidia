<?php
echo "=== TESTE SIMPLES DE VERIFICAÇÃO ===\n\n";

// Teste básico de conexão com a API
$apiUrl = 'https://flyingdonkeys.com.br/api/v1/Lead';
$apiKey = '7a6c08d438ee131971f561fd836b5e15';

echo "🔍 Testando conexão com a API...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '?maxSize=5');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: ' . $apiKey]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 RESULTADO DA CONEXÃO:\n";
echo "   HTTP Code: $httpCode\n";

if ($error) {
    echo "   ❌ Erro cURL: $error\n";
} else {
    echo "   ✅ Conexão OK\n";
}

if ($httpCode == 200 && $response) {
    $data = json_decode($response, true);

    if (isset($data['list']) && count($data['list']) > 0) {
        echo "\n📋 ÚLTIMOS 5 LEADS:\n";

        foreach ($data['list'] as $lead) {
            $createdAt = $lead['createdAt'] ?? 'N/A';
            $firstName = $lead['firstName'] ?? 'N/A';
            $source = $lead['source'] ?? 'N/A';
            $hasOpportunity = !empty($lead['createdOpportunityId']) ? '✅ SIM' : '❌ NÃO';

            echo "   👤 $firstName\n";
            echo "      📅 Criado: $createdAt\n";
            echo "      🏷️ Source: $source\n";
            echo "      🎯 Oportunidade: $hasOpportunity\n";
            echo "      🆔 ID: " . $lead['id'] . "\n\n";
        }

        // Verificar se há leads recentes (últimos 10 minutos)
        $recentLeads = 0;
        $leadsWithOpportunity = 0;

        foreach ($data['list'] as $lead) {
            $createdAt = strtotime($lead['createdAt']);
            $tenMinutesAgo = strtotime('-10 minutes');

            if ($createdAt >= $tenMinutesAgo) {
                $recentLeads++;
                if (!empty($lead['createdOpportunityId'])) {
                    $leadsWithOpportunity++;
                }
            }
        }

        echo "📊 RESUMO DOS ÚLTIMOS 10 MINUTOS:\n";
        echo "   Leads criados: $recentLeads\n";
        echo "   Leads com oportunidade: $leadsWithOpportunity\n";

        if ($recentLeads > 0) {
            if ($leadsWithOpportunity > 0) {
                echo "\n✅ SUCESSO! Workflow funcionando - oportunidades sendo criadas!\n";
            } else {
                echo "\n⚠️ ATENÇÃO! Leads sendo criados mas sem oportunidades.\n";
                echo "🔍 O workflow pode não estar funcionando.\n";
            }
        } else {
            echo "\n❌ Nenhum lead criado nos últimos 10 minutos.\n";
            echo "🔍 Verifique se o endpoint add_travelangels.php está funcionando.\n";
        }
    } else {
        echo "\n❌ Nenhum lead encontrado.\n";
    }
} else {
    echo "\n❌ Erro na API: HTTP $httpCode\n";
    if ($response) {
        echo "Resposta: " . substr($response, 0, 200) . "...\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ VERIFICAÇÃO CONCLUÍDA!\n";
