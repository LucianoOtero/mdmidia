<?php
// ============================================================================
// EXECUÇÃO REAL DO TESTE ADD_LEADSGO_V11.PHP
// ============================================================================

echo "=== EXECUTANDO TESTE REAL DO ADD_LEADSGO_V11.PHP ===\n\n";

// Simular variáveis de ambiente
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/json';
$_SERVER['QUERY_STRING'] = '';

// Dados de teste
$testData = [
    'nome_segurado' => 'Maria Santos Oliveira',
    'email' => 'maria.santos@teste.com.br',
    'telefone_celular' => '11987654321',
    'cep' => '04567-890',
    'cpf_segurado' => '987.654.321-00',
    'marca' => 'Honda',
    'placa' => 'XYZ9876',
    'ano' => '2021',
    'modelo' => 'Civic',
    'endereco' => 'Avenida Paulista',
    'numero' => '1000',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'valor_veiculo' => '95000',
    'data_nascimento' => '1985-05-15',
    'estado_civil' => 'Casada',
    'sexo' => 'Feminino',
    'uso' => 'Particular',
    'pernoite' => 'Garagem',
    'seguradora_preferencia' => 'Bradesco Seguros',
    'valor_preferencia' => '2500',
    'modalidade_seguro' => 'Completo',
    'seguradora_apolice' => 'Itaú Seguros',
    'ci' => 'CI789012'
];

echo "📋 DADOS DE TESTE:\n";
echo "   Nome: " . $testData['nome_segurado'] . "\n";
echo "   Email: " . $testData['email'] . "\n";
echo "   Telefone: " . $testData['telefone_celular'] . "\n";
echo "   Veículo: " . $testData['marca'] . " " . $testData['modelo'] . " " . $testData['placa'] . "\n";
echo "   Seguradora Preferida: " . $testData['seguradora_preferencia'] . "\n\n";

// Simular php://input
$jsonData = json_encode($testData);

echo "🔧 SIMULANDO REQUISIÇÃO POST...\n";
echo "   Método: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "   Content-Type: " . $_SERVER['CONTENT_TYPE'] . "\n";
echo "   Dados JSON: " . substr($jsonData, 0, 100) . "...\n\n";

echo "🚀 EXECUTANDO ADD_LEADSGO_V11.PHP...\n\n";

// Capturar output do arquivo
ob_start();

// Simular php://input
$GLOBALS['php_input'] = $jsonData;

// Incluir o arquivo principal
try {
    include 'add_leadsgo_v11.php';
} catch (Exception $e) {
    echo "❌ ERRO NA EXECUÇÃO: " . $e->getMessage() . "\n";
}

$output = ob_get_clean();

echo "📤 RESPOSTA DO ARQUIVO:\n";
echo $output . "\n\n";

echo "📁 VERIFICANDO LOGS...\n";
if (file_exists('logs_leadsgo.txt')) {
    $logs = file_get_contents('logs_leadsgo.txt');
    $lastLogs = substr($logs, -500); // Últimos 500 caracteres
    echo "📝 ÚLTIMOS LOGS:\n";
    echo $lastLogs . "\n";
} else {
    echo "❌ Arquivo de logs não encontrado\n";
}

echo "\n=== FIM DO TESTE REAL ===\n";




