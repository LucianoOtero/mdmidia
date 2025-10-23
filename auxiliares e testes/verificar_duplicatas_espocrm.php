<?php
/**
 * Script para verificar regras de duplicação no EspoCRM FlyingDonkeys
 */

require_once('class.php');

// Configuração
$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('82d5f667f3a65a9a43341a0705be2b0c');

echo "=== VERIFICANDO REGRAS DE DUPLICAÇÃO ESPOCRM ===\n\n";

try {
    // 1. Tentar obter metadados da entidade Lead
    echo "1. Consultando metadados da entidade Lead...\n";
    $metadata = $client->request('GET', 'metadata/Lead');
    
    if (isset($metadata['duplicateWhereBuilderClassName'])) {
        echo "   ✅ Classe de detecção: " . $metadata['duplicateWhereBuilderClassName'] . "\n";
    } else {
        echo "   ❌ Nenhuma classe personalizada encontrada\n";
    }
    
    if (isset($metadata['duplicateCheckFields'])) {
        echo "   ✅ Campos de verificação: " . implode(', ', $metadata['duplicateCheckFields']) . "\n";
    } else {
        echo "   ❌ Campos de verificação não configurados\n";
    }
    
    echo "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erro ao consultar metadados: " . $e->getMessage() . "\n\n";
}

try {
    // 2. Tentar obter configurações gerais
    echo "2. Consultando configurações gerais...\n";
    $config = $client->request('GET', 'config');
    
    if (isset($config['duplicateCheckFields'])) {
        echo "   ✅ Campos globais de verificação: " . implode(', ', $config['duplicateCheckFields']) . "\n";
    } else {
        echo "   ❌ Configurações globais não encontradas\n";
    }
    
    echo "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erro ao consultar configurações: " . $e->getMessage() . "\n\n";
}

try {
    // 3. Tentar obter informações da entidade Lead
    echo "3. Consultando informações da entidade Lead...\n";
    $leadInfo = $client->request('GET', 'Lead');
    
    if (isset($leadInfo['fields'])) {
        echo "   ✅ Campos disponíveis na entidade Lead:\n";
        foreach ($leadInfo['fields'] as $field => $info) {
            if (isset($info['type'])) {
                echo "      - $field (" . $info['type'] . ")\n";
            }
        }
    }
    
    echo "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erro ao consultar entidade: " . $e->getMessage() . "\n\n";
}

try {
    // 4. Tentar criar um lead de teste para ver o comportamento
    echo "4. Testando comportamento de duplicação...\n";
    
    $testPayload = [
        'firstName' => 'Teste Duplicata',
        'emailAddress' => 'teste.duplicata@example.com',
        'cCelular' => '11999999999',
        'source' => 'Teste'
    ];
    
    $response = $client->request('POST', 'Lead', $testPayload);
    echo "   ✅ Lead de teste criado: " . $response['id'] . "\n";
    
    // Tentar criar duplicata
    try {
        $duplicateResponse = $client->request('POST', 'Lead', $testPayload);
        echo "   ❌ Duplicata foi criada (não deveria): " . $duplicateResponse['id'] . "\n";
    } catch (Exception $e) {
        echo "   ✅ Duplicata foi detectada: " . $e->getMessage() . "\n";
    }
    
    // Limpar lead de teste
    $client->request('DELETE', 'Lead/' . $response['id']);
    echo "   🧹 Lead de teste removido\n";
    
} catch (Exception $e) {
    echo "   ❌ Erro no teste: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DA VERIFICAÇÃO ===\n";
?>


