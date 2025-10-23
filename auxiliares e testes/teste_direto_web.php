<?php
// ============================================================================
// TESTE DIRETO SIMULANDO AMBIENTE WEB
// ============================================================================

echo "=== TESTE DIRETO SIMULANDO AMBIENTE WEB ===\n\n";

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

// Simular ambiente web
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/json';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/mdmidia/add_leadsgo_v11.php';

// Simular php://input
$jsonData = json_encode($testData);

echo "🔧 SIMULANDO AMBIENTE WEB:\n";
echo "   REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "   CONTENT_TYPE: " . $_SERVER['CONTENT_TYPE'] . "\n";
echo "   JSON Data: " . substr($jsonData, 0, 100) . "...\n\n";

// Criar um arquivo temporário para simular php://input
$tempFile = tempnam(sys_get_temp_dir(), 'php_input');
file_put_contents($tempFile, $jsonData);

// Redirecionar php://input para o arquivo temporário
stream_wrapper_unregister('php');
stream_wrapper_register('php', 'TestPhpWrapper');

class TestPhpWrapper {
    private $position = 0;
    private $data;
    
    public function stream_open($path, $mode, $options, &$opened_path) {
        if ($mode === 'r' && strpos($path, 'php://input') !== false) {
            global $jsonData;
            $this->data = $jsonData;
            return true;
        }
        return false;
    }
    
    public function stream_read($count) {
        $ret = substr($this->data, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }
    
    public function stream_eof() {
        return $this->position >= strlen($this->data);
    }
    
    public function stream_stat() {
        return [];
    }
}

echo "🚀 EXECUTANDO ADD_LEADSGO_V11.PHP...\n\n";

// Capturar output
ob_start();

try {
    // Incluir o arquivo principal
    include 'add_leadsgo_v11.php';
} catch (Exception $e) {
    echo "❌ ERRO NA EXECUÇÃO: " . $e->getMessage() . "\n";
} catch (Error $e) {
    echo "❌ ERRO FATAL: " . $e->getMessage() . "\n";
}

$output = ob_get_clean();

echo "📤 RESPOSTA DO ARQUIVO:\n";
echo $output . "\n\n";

// Tentar decodificar JSON da resposta
$decodedResponse = json_decode($output, true);
if ($decodedResponse) {
    echo "📋 RESPOSTA DECODIFICADA:\n";
    echo "   Status: " . ($decodedResponse['status'] ?? 'N/A') . "\n";
    echo "   Message: " . ($decodedResponse['message'] ?? 'N/A') . "\n";
    echo "   Method: " . ($decodedResponse['method'] ?? 'N/A') . "\n";
    echo "   Content-Type: " . ($decodedResponse['content_type'] ?? 'N/A') . "\n\n";
    
    if (isset($decodedResponse['status']) && $decodedResponse['status'] === 'success') {
        echo "✅ TESTE BEM-SUCEDIDO!\n";
    } else {
        echo "❌ TESTE FALHOU!\n";
    }
} else {
    echo "❌ Resposta não é JSON válido\n";
}

echo "\n📁 VERIFICANDO LOGS...\n";
if (file_exists('logs_leadsgo.txt')) {
    $logs = file_get_contents('logs_leadsgo.txt');
    $lastLogs = substr($logs, -1000); // Últimos 1000 caracteres
    echo "📝 ÚLTIMOS LOGS:\n";
    echo $lastLogs . "\n";
} else {
    echo "❌ Arquivo de logs não encontrado\n";
}

// Limpar arquivo temporário
unlink($tempFile);

echo "\n=== FIM DO TESTE DIRETO ===\n";




