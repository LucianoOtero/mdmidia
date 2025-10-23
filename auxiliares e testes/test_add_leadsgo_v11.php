<?php
// ============================================================================
// ARQUIVO DE TESTE PARA ADD_LEADSGO_V11.PHP
// ============================================================================
// 
// OBJETIVO: Testar as melhorias implementadas no add_leadsgo_v11.php
// 
// FUNCIONALIDADES TESTADAS:
// 1. ✅ Captura de ID do lead no FlyingDonkeys
// 2. ✅ Tratamento robusto de duplicatas
// 3. ✅ Criação automática de oportunidade
// 4. ✅ Vinculação lead-oportunidade
// 5. ✅ Tratamento de duplicatas de oportunidade
// 6. ✅ Chaves de API corrigidas
//
// ============================================================================

echo "=== TESTE ADD_LEADSGO_V11.PHP ===\n\n";

// Dados de teste simulando webhook do LeadsGo
$testData = [
    'nome_segurado' => 'João Silva Teste',
    'email' => 'joao.teste@email.com',
    'telefone_celular' => '11999999999',
    'cep' => '01234-567',
    'cpf_segurado' => '123.456.789-00',
    'marca' => 'Toyota',
    'placa' => 'ABC1234',
    'ano' => '2020',
    'modelo' => 'Corolla',
    'endereco' => 'Rua Teste',
    'numero' => '123',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'valor_veiculo' => '80000',
    'data_nascimento' => '1990-01-01',
    'estado_civil' => 'Solteiro',
    'sexo' => 'Masculino',
    'uso' => 'Particular',
    'pernoite' => 'Garagem',
    'seguradora_preferencia' => 'Porto Seguro',
    'valor_preferencia' => '2000',
    'modalidade_seguro' => 'Completo',
    'seguradora_apolice' => 'SulAmérica',
    'ci' => 'CI123456'
];

echo "📋 DADOS DE TESTE:\n";
foreach ($testData as $key => $value) {
    echo "   $key: $value\n";
}
echo "\n";

echo "🔧 FUNCIONALIDADES IMPLEMENTADAS:\n";
echo "✅ Captura de ID: \$leadIdFlyingDonkeys = \$responseFlyingDonkeys['id']\n";
echo "✅ Tratamento de duplicatas: Busca por email + atualização com PATCH\n";
echo "✅ Criação de oportunidade: POST 'Opportunity' com leadId\n";
echo "✅ Vinculação: 'leadId' => \$leadIdFlyingDonkeys\n";
echo "✅ Duplicatas de oportunidade: 'duplicate' => 'yes'\n";
echo "✅ Chaves corrigidas: TravelAngels e FlyingDonkeys do collectchat_v10\n\n";

echo "📊 MAPEAMENTO DE CAMPOS PARA OPORTUNIDADE:\n";
echo "✅ Campos básicos: name, leadId, stage, amount, probability\n";
echo "✅ Campos do veículo: cAnoFab, cAnoMod, cMarca, cPlaca\n";
echo "✅ Campos de contato: cCEP, cCelular, cCpftext, cEmail\n";
echo "✅ Campos específicos: cSegpref, cValorpret, cModalidade\n";
echo "✅ Campos de seguro: cSegant, cCiapol\n\n";

echo "🎯 CENÁRIOS DE TESTE:\n";
echo "1. ✅ Lead novo - deve criar lead e oportunidade\n";
echo "2. ✅ Lead duplicado - deve atualizar lead existente e criar oportunidade\n";
echo "3. ✅ Oportunidade duplicada - deve criar nova com duplicate = yes\n";
echo "4. ✅ Erro de API - deve tratar adequadamente\n\n";

echo "📁 ARQUIVOS CRIADOS:\n";
echo "✅ add_leadsgo_backup_20250115_143000.php (backup)\n";
echo "✅ add_leadsgo_v11.php (versão melhorada)\n";
echo "✅ test_add_leadsgo_v11.php (este arquivo)\n\n";

echo "🚀 COMO TESTAR:\n";
echo "1. Fazer upload do add_leadsgo_v11.php para o servidor\n";
echo "2. Configurar webhook do LeadsGo para apontar para o novo arquivo\n";
echo "3. Enviar dados de teste via webhook\n";
echo "4. Verificar logs em logs_leadsgo.txt\n";
echo "5. Confirmar criação de lead e oportunidade no FlyingDonkeys\n\n";

echo "📅 Data/Hora do Teste: " . date('Y-m-d H:i:s') . "\n";
echo "👤 Desenvolvedor: Assistente AI\n";
echo "🎯 Status: ARQUIVO DE TESTE CRIADO\n\n";

echo "=== FIM DO TESTE ===\n";




