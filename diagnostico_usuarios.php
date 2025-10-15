<?php
require_once('class.php');

echo "=== DIAGNÓSTICO DE USUÁRIOS FLYINGDONKEYS ===\n\n";

$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('7a6c08d438ee131971f561fd836b5e15');

echo "1. Verificando usuário atual...\n";

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
    echo "✅ Usuário atual conectado:\n";
    echo "   👤 Nome: " . ($userData['userName'] ?? 'N/A') . "\n";
    echo "   🆔 ID: " . ($userData['id'] ?? 'N/A') . "\n";
    echo "   📊 Tipo: " . ($userData['type'] ?? 'N/A') . "\n";
    echo "   ✅ Ativo: " . ($userData['isActive'] ? 'Sim' : 'Não') . "\n\n";

    echo "2. Listando todos os usuários do sistema...\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://flyingdonkeys.com.br/api/v1/User');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: 7a6c08d438ee131971f561fd836b5e15']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $usersResponse = curl_exec($ch);
    $usersHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($usersHttpCode == 200) {
        $users = json_decode($usersResponse, true);
        echo "📋 Total de usuários: " . count($users['list']) . "\n\n";

        $apiUsers = [];
        $activeUsers = 0;

        foreach ($users['list'] as $user) {
            if ($user['isActive']) {
                $activeUsers++;
            }

            // Procurar usuários relacionados a API
            if (
                stripos($user['userName'], 'api') !== false ||
                stripos($user['userName'], 'Api') !== false ||
                stripos($user['userName'], 'API') !== false ||
                $user['type'] == 'api'
            ) {
                $apiUsers[] = $user;
            }

            echo "👤 " . $user['userName'] . " - " . ($user['isActive'] ? '✅ ATIVO' : '❌ INATIVO') . "\n";
            echo "   🆔 ID: " . $user['id'] . "\n";
            echo "   📊 Tipo: " . ($user['type'] ?? 'N/A') . "\n";
            echo "   📅 Criado: " . ($user['createdAt'] ?? 'N/A') . "\n\n";
        }

        echo "📊 RESUMO DE USUÁRIOS:\n";
        echo "   Total: " . count($users['list']) . "\n";
        echo "   Ativos: $activeUsers\n";
        echo "   Relacionados a API: " . count($apiUsers) . "\n\n";

        if (!empty($apiUsers)) {
            echo "🎯 USUÁRIOS API ENCONTRADOS:\n";
            foreach ($apiUsers as $apiUser) {
                echo "   👤 " . $apiUser['userName'] . " - " . ($apiUser['isActive'] ? '✅ ATIVO' : '❌ INATIVO') . "\n";
                echo "   🆔 ID: " . $apiUser['id'] . "\n";
                echo "   📊 Tipo: " . ($apiUser['type'] ?? 'N/A') . "\n\n";
            }
        } else {
            echo "⚠️ NENHUM USUÁRIO API ENCONTRADO!\n\n";
        }
    } else {
        echo "❌ Erro ao buscar usuários - HTTP: $usersHttpCode\n";
    }

    echo "3. Verificando permissões do usuário atual...\n";

    if (isset($userData['permissions'])) {
        $permissions = $userData['permissions'];
        echo "📋 Permissões do usuário atual:\n";

        $relevantEntities = ['lead', 'opportunity', 'workflow'];
        foreach ($relevantEntities as $entity) {
            if (isset($permissions[$entity])) {
                $entityPerms = $permissions[$entity];
                echo "   📝 $entity:\n";
                echo "      Create: " . ($entityPerms['create'] ? '✅' : '❌') . "\n";
                echo "      Read: " . ($entityPerms['read'] ? '✅' : '❌') . "\n";
                echo "      Edit: " . ($entityPerms['edit'] ? '✅' : '❌') . "\n";
                echo "      Delete: " . ($entityPerms['delete'] ? '✅' : '❌') . "\n";
            }
        }
    } else {
        echo "⚠️ Permissões não disponíveis para visualização\n";
    }
} else {
    echo "❌ Erro ao conectar - HTTP: $httpCode\n";
    echo "Resposta: " . substr($response, 0, 200) . "...\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 DIAGNÓSTICO DE USUÁRIOS:\n";
echo str_repeat("=", 60) . "\n";

echo "\n💡 PROBLEMA IDENTIFICADO:\n";
echo "Se não existe usuário 'api', os workflows podem não estar executando\n";
echo "porque não há um usuário específico para execução de workflows.\n\n";

echo "📋 SOLUÇÕES POSSÍVEIS:\n";
echo "1. Criar um usuário 'api' específico para workflows\n";
echo "2. Usar um usuário administrativo existente\n";
echo "3. Verificar se há outro usuário com permissões adequadas\n";
echo "4. Configurar workflows para executar com usuário específico\n\n";

echo "🔧 PRÓXIMOS PASSOS:\n";
echo "1. Acesse: https://flyingdonkeys.com.br\n";
echo "2. Vá para: Administração → Usuários\n";
echo "3. Crie um usuário 'api' ou use um existente\n";
echo "4. Configure permissões adequadas para Lead e Opportunity\n";
echo "5. Verifique configuração dos workflows\n";

echo "\n✅ DIAGNÓSTICO CONCLUÍDO!\n";
