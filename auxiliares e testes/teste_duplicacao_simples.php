<?php
/**
 * Teste simples para entender regras de duplicação
 */

require_once('class.php');

$client = new EspoApiClient('https://flyingdonkeys.com.br');
$client->setApiKey('82d5f667f3a65a9a43341a0705be2b0c');

echo "=== TESTE DE DUPLICAÇÃO ESPOCRM ===\n\n";

// Teste 1: Mesmo nome, emails diferentes
echo "TESTE 1: Mesmo nome, emails diferentes\n";
$payload1 = [
    'firstName' => 'João Silva',
    'emailAddress' => 'joao.silva.teste1@example.com',
    'cCelular' => '11999999999',
    'source' => 'Site'
];

try {
    $response1 = $client->request('POST', 'Lead', $payload1);
    echo "✅ Lead 1 criado: " . $response1['id'] . "\n";
    
    // Tentar criar com mesmo nome, email diferente
    $payload2 = [
        'firstName' => 'João Silva',
        'emailAddress' => 'joao.silva.teste2@example.com',
        'cCelular' => '11888888888',
        'source' => 'Site'
    ];
    
    try {
        $response2 = $client->request('POST', 'Lead', $payload2);
        echo "❌ Lead 2 criado (não deveria): " . $response2['id'] . "\n";
        $client->request('DELETE', 'Lead/' . $response2['id']);
    } catch (Exception $e) {
        echo "✅ Duplicata detectada por nome: " . substr($e->getMessage(), 0, 100) . "...\n";
    }
    
    $client->request('DELETE', 'Lead/' . $response1['id']);
    
} catch (Exception $e) {
    echo "❌ Erro no teste 1: " . $e->getMessage() . "\n";
}

echo "\n";

// Teste 2: Mesmo email, nomes diferentes
echo "TESTE 2: Mesmo email, nomes diferentes\n";
$payload3 = [
    'firstName' => 'Maria Santos',
    'emailAddress' => 'maria.santos.teste@example.com',
    'cCelular' => '11777777777',
    'source' => 'Site'
];

try {
    $response3 = $client->request('POST', 'Lead', $payload3);
    echo "✅ Lead 3 criado: " . $response3['id'] . "\n";
    
    // Tentar criar com mesmo email, nome diferente
    $payload4 = [
        'firstName' => 'Pedro Costa',
        'emailAddress' => 'maria.santos.teste@example.com',
        'cCelular' => '11666666666',
        'source' => 'Site'
    ];
    
    try {
        $response4 = $client->request('POST', 'Lead', $payload4);
        echo "❌ Lead 4 criado (não deveria): " . $response4['id'] . "\n";
        $client->request('DELETE', 'Lead/' . $response4['id']);
    } catch (Exception $e) {
        echo "✅ Duplicata detectada por email: " . substr($e->getMessage(), 0, 100) . "...\n";
    }
    
    $client->request('DELETE', 'Lead/' . $response3['id']);
    
} catch (Exception $e) {
    echo "❌ Erro no teste 2: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DOS TESTES ===\n";
?>


