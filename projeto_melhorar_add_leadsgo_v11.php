<?php
// ============================================================================
// PROJETO: MELHORAR ADD_LEADSGO_V11 COM LÓGICA DO COLLECTCHAT_V10
// ============================================================================
// 
// OBJETIVO: Implementar no add_leadsgo_v11 as melhorias identificadas do add_collectchat_v10
// 
// PROBLEMAS IDENTIFICADOS E CORRIGIDOS:
// 
// ❌ PROBLEMA 1: INCONSISTÊNCIA DE CHAVES DE API
//    - add_leadsgo.php: '7a6c08d438ee131971f561fd836b5e15'
//    - add_leadsgo_v11.php: '82d5f667f3a65a9a43341a0705be2b0c'
//    - add_collect_chat_v10.php: 'd5bcb42f62d1d96f8090a1002b792335' (TravelAngels)
//    - add_collect_chat_v10.php: '82d5f667f3a65a9a43341a0705be2b0c' (FlyingDonkeys)
//    ✅ CORREÇÃO: Usar chaves do add_collectchat_v10 (referência)
//
// ❌ PROBLEMA 2: FUNÇÃO findLeadByEmail NÃO USADA
//    - Função existe mas não é chamada
//    ✅ CORREÇÃO: Implementar uso da função no tratamento de duplicatas
//
// ❌ PROBLEMA 3: FALTA DA VARIÁVEL leadIdFlyingDonkeys
//    - Variável não declarada no código atual
//    ✅ CORREÇÃO: Declarar e inicializar a variável
//
// ❌ PROBLEMA 4: MAPEAMENTO DE CAMPOS DIFERENTE
//    - LeadsGo usa campos diferentes do CollectChat
//    ✅ CORREÇÃO: Adaptar mapeamento para campos do LeadsGo
//
// ❌ PROBLEMA 5: ESTRUTURA DE DADOS DIFERENTE
//    - LeadsGo recebe dados estruturados vs CollectChat processa API
//    ✅ CORREÇÃO: Adaptar para estrutura do LeadsGo
//
// MELHORIAS A IMPLEMENTAR:
// 
// 1. ✅ CAPTURA DE ID DO LEAD
//    - Declarar $leadIdFlyingDonkeys = null
//    - Capturar $leadIdFlyingDonkeys = $responseFlyingDonkeys['id']
//    - Usar ID para vincular com oportunidade
//
// 2. ✅ TRATAMENTO ROBUSTO DE DUPLICATAS
//    - Buscar lead existente por email quando erro 409
//    - Atualizar lead existente com PATCH
//    - Manter ID do lead para criação de oportunidade
//
// 3. ✅ CRIAÇÃO AUTOMÁTICA DE OPORTUNIDADE
//    - Criar oportunidade automaticamente após lead
//    - Mapear campos do lead para oportunidade (adaptados para LeadsGo)
//    - Vincular oportunidade com lead via leadId
//
// 4. ✅ TRATAMENTO DE DUPLICATAS DE OPORTUNIDADE
//    - Detectar erro 409 em oportunidade
//    - Usar 'duplicate' => 'yes' para forçar criação
//
// 5. ✅ MAPEAMENTO COMPLETO DE CAMPOS (ADAPTADO PARA LEADSGO)
//    - Mapear campos específicos do LeadsGo para oportunidade
//    - Incluir campos de seguro específicos
//
// ============================================================================

echo "=== PROJETO: MELHORAR ADD_LEADSGO_V11 ===\n\n";

echo "📋 TAREFAS DO PROJETO:\n";
echo "1. ✅ Criar backup do add_leadsgo.php com data/hora atual\n";
echo "2. ✅ Criar cópia do add_leadsgo.php para add_leadsgo_v11.php\n";
echo "3. ✅ Implementar captura de ID do lead no FlyingDonkeys\n";
echo "4. ✅ Implementar tratamento robusto de duplicatas\n";
echo "5. ✅ Implementar criação automática de oportunidade\n";
echo "6. ✅ Implementar vinculação lead-oportunidade\n";
echo "7. ✅ Implementar tratamento de duplicatas de oportunidade\n";
echo "8. ✅ Criar arquivo de teste para add_leadsgo_v11.php\n";
echo "9. ✅ Avisar quando projeto estiver pronto para deploy\n\n";

echo "🔧 MELHORIAS A IMPLEMENTAR (CORRIGIDAS):\n";
echo "- ✅ Declarar variável: \$leadIdFlyingDonkeys = null\n";
echo "- ✅ Captura de ID: \$leadIdFlyingDonkeys = \$responseFlyingDonkeys['id']\n";
echo "- ✅ Usar função existente: findLeadByEmail(\$email, \$clientFlyingDonkeys, \$logs)\n";
echo "- ✅ Atualização de lead: PATCH 'Lead/' . \$existingLead['id']\n";
echo "- ✅ Criação de oportunidade: POST 'Opportunity' com leadId\n";
echo "- ✅ Tratamento de duplicatas: 'duplicate' => 'yes'\n";
echo "- ✅ Mapeamento adaptado para campos do LeadsGo\n\n";

echo "🚨 CORREÇÕES CRÍTICAS APLICADAS:\n";
echo "- ✅ Chave API TravelAngels: 'd5bcb42f62d1d96f8090a1002b792335' (do collectchat_v10)\n";
echo "- ✅ Chave API FlyingDonkeys: '82d5f667f3a65a9a43341a0705be2b0c' (do collectchat_v10)\n";
echo "- ✅ Função findLeadByEmail será utilizada\n";
echo "- ✅ Variável leadIdFlyingDonkeys será declarada\n";
echo "- ✅ Mapeamento adaptado para estrutura do LeadsGo\n";
echo "- ✅ Campos específicos do LeadsGo para oportunidade\n\n";

echo "📁 ARQUIVOS A SEREM CRIADOS/MODIFICADOS:\n";
echo "- add_leadsgo_backup_" . date('Ymd_His') . ".php (backup)\n";
echo "- add_leadsgo_v11.php (versão melhorada)\n";
echo "- test_add_leadsgo_v11.php (arquivo de teste)\n\n";

echo "🎯 RESULTADO ESPERADO:\n";
echo "- add_leadsgo_v11.php com lógica avançada igual ao collectchat_v10\n";
echo "- Tratamento robusto de duplicatas\n";
echo "- Criação automática de oportunidades\n";
echo "- Vinculação lead-oportunidade\n";
echo "- Arquivo de teste funcional\n\n";

echo "💻 CÓDIGO ESPECÍFICO A IMPLEMENTAR:\n\n";

echo "1. DECLARAÇÃO DE VARIÁVEL:\n";
echo "   \$leadIdFlyingDonkeys = null;\n\n";

echo "2. CORREÇÃO DAS CHAVES DE API (DO COLLECTCHAT_V10):\n";
echo "   \$client->setApiKey('d5bcb42f62d1d96f8090a1002b792335'); // TravelAngels\n";
echo "   \$clientFlyingDonkeys->setApiKey('82d5f667f3a65a9a43341a0705be2b0c'); // FlyingDonkeys\n\n";

echo "3. CAPTURA DE ID DO LEAD:\n";
echo "   \$leadIdFlyingDonkeys = \$responseFlyingDonkeys['id'];\n\n";

echo "4. TRATAMENTO DE DUPLICATAS:\n";
echo "   if (strpos(\$errorMessage, '409') !== false) {\n";
echo "       \$existingLead = findLeadByEmail(\$email, \$clientFlyingDonkeys, \$logs);\n";
echo "       if (\$existingLead) {\n";
echo "           \$updateResponse = \$clientFlyingDonkeys->request('PATCH', 'Lead/' . \$existingLead['id'], \$payload);\n";
echo "           \$leadIdFlyingDonkeys = \$existingLead['id'];\n";
echo "       }\n";
echo "   }\n\n";

echo "5. CRIAÇÃO DE OPORTUNIDADE (CAMPOS ADAPTADOS PARA LEADSGO):\n";
echo "   \$opportunityPayload = [\n";
echo "       'name' => \$name,\n";
echo "       'leadId' => \$leadIdFlyingDonkeys,\n";
echo "       'stage' => 'Novo Sem Contato',\n";
echo "       'amount' => 0,\n";
echo "       'probability' => 10,\n";
echo "       'cAnoFab' => \$ano,\n";
echo "       'cAnoMod' => \$ano,\n";
echo "       'cCEP' => \$cep,\n";
echo "       'cCelular' => \$telefone,\n";
echo "       'cCpftext' => \$cpf,\n";
echo "       'cMarca' => \$marca,\n";
echo "       'cPlaca' => \$placa,\n";
echo "       'cWebpage' => \$webpage,\n";
echo "       'cEmail' => \$email,\n";
echo "       'cEmailAdress' => \$email,\n";
echo "       'source' => \$source,\n";
echo "       'cSegpref' => \$seguradoraPref,\n";
echo "       'cValorpret' => \$valorPref,\n";
echo "       'cModalidade' => \$modalidade,\n";
echo "       'cSegant' => \$seguradoraAnt,\n";
echo "       'cCiapol' => \$ciApol,\n";
echo "   ];\n\n";

echo "6. TRATAMENTO DE DUPLICATAS DE OPORTUNIDADE:\n";
echo "   if (strpos(\$errorMessage, '409') !== false) {\n";
echo "       \$opportunityPayload['duplicate'] = 'yes';\n";
echo "       \$responseOpportunity = \$clientFlyingDonkeys->request('POST', 'Opportunity', \$opportunityPayload);\n";
echo "   }\n\n";

echo "📅 Data/Hora do Projeto: " . date('Y-m-d H:i:s') . "\n";
echo "👤 Desenvolvedor: Assistente AI\n";
echo "🎯 Status: PROJETO CORRIGIDO - PRONTO PARA IMPLEMENTAÇÃO\n\n";

echo "=== FIM DO PROJETO CORRIGIDO ===\n";
