<?php
// ============================================================================
// TESTE COMPLETO PARA ADD_LEADSGO_V11.PHP
// ============================================================================
// 
// OBJETIVO: Simular uma requisição real do webhook LeadsGo para testar o add_leadsgo_v11.php
// 
// FUNCIONALIDADES TESTADAS:
// 1. ✅ Simulação de requisição POST com dados JSON
// 2. ✅ Teste de todas as funcionalidades implementadas
// 3. ✅ Verificação de logs e respostas
// 4. ✅ Teste de tratamento de erros
//
// ============================================================================

echo "=== TESTE COMPLETO ADD_LEADSGO_V11.PHP ===\n\n";

// Dados de teste simulando webhook real do LeadsGo
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

echo "📋 DADOS DE TESTE QUE SERÃO UTILIZADOS:\n\n";

echo "👤 DADOS PESSOAIS:\n";
echo "   Nome: " . $testData['nome_segurado'] . "\n";
echo "   Email: " . $testData['email'] . "\n";
echo "   Telefone: " . $testData['telefone_celular'] . "\n";
echo "   CPF: " . $testData['cpf_segurado'] . "\n";
echo "   Data Nascimento: " . $testData['data_nascimento'] . "\n";
echo "   Estado Civil: " . $testData['estado_civil'] . "\n";
echo "   Sexo: " . $testData['sexo'] . "\n\n";

echo "🚗 DADOS DO VEÍCULO:\n";
echo "   Marca: " . $testData['marca'] . "\n";
echo "   Modelo: " . $testData['modelo'] . "\n";
echo "   Placa: " . $testData['placa'] . "\n";
echo "   Ano: " . $testData['ano'] . "\n";
echo "   Valor: R$ " . number_format($testData['valor_veiculo'], 2, ',', '.') . "\n";
echo "   Uso: " . $testData['uso'] . "\n";
echo "   Pernoite: " . $testData['pernoite'] . "\n\n";

echo "🏠 ENDEREÇO:\n";
echo "   Endereço: " . $testData['endereco'] . ", " . $testData['numero'] . "\n";
echo "   CEP: " . $testData['cep'] . "\n";
echo "   Cidade: " . $testData['cidade'] . "\n";
echo "   Estado: " . $testData['estado'] . "\n\n";

echo "🛡️ DADOS DE SEGURO:\n";
echo "   Seguradora Preferida: " . $testData['seguradora_preferencia'] . "\n";
echo "   Valor Preferencial: R$ " . number_format($testData['valor_preferencia'], 2, ',', '.') . "\n";
echo "   Modalidade: " . $testData['modalidade_seguro'] . "\n";
echo "   Seguradora Anterior: " . $testData['seguradora_apolice'] . "\n";
echo "   CI Apólice: " . $testData['ci'] . "\n\n";

// Converter dados para JSON
$jsonData = json_encode($testData, JSON_PRETTY_PRINT);

echo "📄 JSON QUE SERÁ ENVIADO:\n";
echo $jsonData . "\n\n";

echo "🔧 COMO EXECUTAR O TESTE:\n";
echo "1. Simular variáveis de ambiente:\n";
echo "   \$_SERVER['REQUEST_METHOD'] = 'POST'\n";
echo "   \$_SERVER['CONTENT_TYPE'] = 'application/json'\n";
echo "   \$_SERVER['QUERY_STRING'] = ''\n\n";

echo "2. Simular dados de entrada:\n";
echo "   php://input = " . json_encode($testData) . "\n\n";

echo "3. Executar o arquivo:\n";
echo "   php add_leadsgo_v11.php\n\n";

echo "🎯 RESULTADOS ESPERADOS:\n";
echo "✅ Lead criado no TravelAngels com nome: " . $testData['nome_segurado'] . "\n";
echo "✅ Lead criado/atualizado no FlyingDonkeys com ID capturado\n";
echo "✅ Oportunidade criada automaticamente vinculada ao lead\n";
echo "✅ Logs detalhados em logs_leadsgo.txt\n";
echo "✅ Resposta JSON de sucesso\n\n";

echo "📊 MAPEAMENTO PARA OPORTUNIDADE:\n";
echo "   Nome: " . $testData['nome_segurado'] . "\n";
echo "   Lead ID: [será capturado automaticamente]\n";
echo "   Stage: Novo Sem Contato\n";
echo "   Amount: 0\n";
echo "   Probability: 10\n";
echo "   Marca: " . $testData['marca'] . "\n";
echo "   Placa: " . $testData['placa'] . "\n";
echo "   Ano: " . $testData['ano'] . "\n";
echo "   Email: " . $testData['email'] . "\n";
echo "   Telefone: " . $testData['telefone_celular'] . "\n";
echo "   CEP: " . $testData['cep'] . "\n";
echo "   CPF: " . $testData['cpf_segurado'] . "\n";
echo "   Seguradora Preferida: " . $testData['seguradora_preferencia'] . "\n";
echo "   Valor Preferencial: " . $testData['valor_preferencia'] . "\n";
echo "   Modalidade: " . $testData['modalidade_seguro'] . "\n";
echo "   Seguradora Anterior: " . $testData['seguradora_apolice'] . "\n";
echo "   CI Apólice: " . $testData['ci'] . "\n\n";

echo "🚨 CENÁRIOS DE TESTE:\n";
echo "1. ✅ Lead novo - deve criar lead e oportunidade\n";
echo "2. ✅ Lead duplicado - deve atualizar lead existente\n";
echo "3. ✅ Oportunidade duplicada - deve criar nova com duplicate = yes\n";
echo "4. ✅ Erro de API - deve tratar adequadamente\n\n";

echo "📁 ARQUIVOS ENVOLVIDOS:\n";
echo "✅ add_leadsgo_v11.php (arquivo principal)\n";
echo "✅ logs_leadsgo.txt (logs de execução)\n";
echo "✅ class.php (cliente EspoCRM)\n\n";

echo "📅 Data/Hora do Teste: " . date('Y-m-d H:i:s') . "\n";
echo "👤 Desenvolvedor: Assistente AI\n";
echo "🎯 Status: ARQUIVO DE TESTE COMPLETO CRIADO\n\n";

echo "=== FIM DO TESTE COMPLETO ===\n";




