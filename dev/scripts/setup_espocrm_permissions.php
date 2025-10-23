<?php

/**
 * CONFIGURAÇÃO DE PERMISSÕES ESPOCRM DESENVOLVIMENTO
 * dev/scripts/setup_espocrm_permissions.php
 * 
 * Script para configurar permissões e regras do EspoCRM de desenvolvimento
 */

echo "🔐 CONFIGURAÇÃO DE PERMISSÕES ESPOCRM DESENVOLVIMENTO\n";
echo "===================================================\n\n";

// Credenciais geradas
$API_KEY = 'nEgf0Zwt7b09cGwKGuqSqdPgPpmZHzJU';
$API_USER_EMAIL = 'api-dev@flyingdonkeys.com.br';
$API_USER_PASSWORD = '4vJMGl9%@DtELFqS';
$ESPOCRM_URL = 'https://dev.flyingdonkeys.com.br';

echo "📋 CREDENCIAIS ATUAIS:\n";
echo "======================\n";
echo "🔑 API Key: {$API_KEY}\n";
echo "📧 API User Email: {$API_USER_EMAIL}\n";
echo "🔒 API User Password: {$API_USER_PASSWORD}\n";
echo "🌐 EspoCRM URL: {$ESPOCRM_URL}\n\n";

echo "🔧 CONFIGURAÇÃO MANUAL NO ESPOCRM:\n";
echo "===================================\n\n";

echo "1️⃣ CRIAR USUÁRIO API:\n";
echo "---------------------\n";
echo "• Acesse: {$ESPOCRM_URL}\n";
echo "• Faça login como administrador\n";
echo "• Vá em Administration → Users\n";
echo "• Clique em 'Create User'\n";
echo "• Configure:\n";
echo "  - Name: API Webhook Dev\n";
echo "  - Email: {$API_USER_EMAIL}\n";
echo "  - Password: {$API_USER_PASSWORD}\n";
echo "  - Role: Admin (para desenvolvimento)\n";
echo "  - Teams: Default\n";
echo "  - Type: Regular\n\n";

echo "2️⃣ CONFIGURAR PERMISSÕES DO USUÁRIO:\n";
echo "------------------------------------\n";
echo "• Após criar o usuário, clique nele para editar\n";
echo "• Vá na aba 'Access'\n";
echo "• Configure as permissões:\n\n";

echo "📊 PERMISSÕES NECESSÁRIAS:\n";
echo "==========================\n";
echo "• Lead: Create, Read, Edit, Delete\n";
echo "• Opportunity: Create, Read, Edit, Delete\n";
echo "• Contact: Create, Read, Edit, Delete\n";
echo "• Account: Create, Read, Edit, Delete\n";
echo "• Task: Create, Read, Edit, Delete\n";
echo "• Call: Create, Read, Edit, Delete\n";
echo "• Meeting: Create, Read, Edit, Delete\n";
echo "• Email: Create, Read, Edit, Delete\n";
echo "• Document: Create, Read, Edit, Delete\n";
echo "• Attachment: Create, Read, Edit, Delete\n\n";

echo "3️⃣ CRIAR API USER:\n";
echo "-------------------\n";
echo "• Vá em Administration → API Users\n";
echo "• Clique em 'Create API User'\n";
echo "• Configure:\n";
echo "  - User: API Webhook Dev\n";
echo "  - Key: {$API_KEY}\n";
echo "  - Allowed IP: * (para desenvolvimento)\n";
echo "  - Secret Key: (deixe vazio ou gere uma)\n\n";

echo "4️⃣ CONFIGURAR ROLE (se necessário):\n";
echo "----------------------------------\n";
echo "• Vá em Administration → Roles\n";
echo "• Crie uma nova role 'API Webhook Dev' ou use 'Admin'\n";
echo "• Configure as permissões:\n";
echo "  - Data: All\n";
echo "  - Field Level: All\n";
echo "  - Portal: No\n";
echo "  - Mass Update: Yes\n";
echo "  - Export: Yes\n";
echo "  - Import: Yes\n";
echo "  - Assignment: Yes\n";
echo "  - User Permission: Yes\n\n";

echo "5️⃣ CONFIGURAR TEAMS (se necessário):\n";
echo "-------------------------------------\n";
echo "• Vá em Administration → Teams\n";
echo "• Crie um team 'API Development' ou use 'Default'\n";
echo "• Adicione o usuário API ao team\n\n";

echo "6️⃣ CONFIGURAR ENTIDADES ESPECÍFICAS:\n";
echo "------------------------------------\n";
echo "• Vá em Administration → Entity Manager\n";
echo "• Para cada entidade (Lead, Opportunity, etc.):\n";
echo "  - Clique na entidade\n";
echo "  - Vá na aba 'Access'\n";
echo "  - Configure:\n";
echo "    * Create: Yes\n";
echo "    * Read: Yes\n";
echo "    * Edit: Yes\n";
echo "    * Delete: Yes\n";
echo "    * Stream: Yes\n";
echo "    * Assignment: Yes\n";
echo "    * User Permission: Yes\n\n";

echo "7️⃣ CONFIGURAR CAMPOS PERSONALIZADOS:\n";
echo "-------------------------------------\n";
echo "• Vá em Administration → Entity Manager\n";
echo "• Para Lead e Opportunity:\n";
echo "  - Adicione campos se necessário:\n";
echo "    * leadSource (text)\n";
echo "    * source (text)\n";
echo "    * gclid (text)\n";
echo "    * utm_source (text)\n";
echo "    * utm_campaign (text)\n\n";

echo "🧪 TESTE DE CONECTIVIDADE:\n";
echo "==========================\n";
echo "Após configurar tudo, execute:\n";
echo "php dev/scripts/test_espocrm_connection.php\n\n";

echo "🔍 VERIFICAÇÃO MANUAL:\n";
echo "======================\n";
echo "1. Teste login: {$ESPOCRM_URL}\n";
echo "2. Email: {$API_USER_EMAIL}\n";
echo "3. Senha: {$API_USER_PASSWORD}\n";
echo "4. Verifique se consegue acessar todas as entidades\n";
echo "5. Teste criar um Lead manualmente\n";
echo "6. Teste criar uma Opportunity manualmente\n\n";

echo "📞 SUPORTE:\n";
echo "===========\n";
echo "Se houver problemas:\n";
echo "• Verifique os logs do EspoCRM\n";
echo "• Confirme se o usuário tem permissões adequadas\n";
echo "• Teste com role 'Admin' primeiro\n";
echo "• Verifique se a API Key está correta\n\n";

echo "✅ CHECKLIST FINAL:\n";
echo "===================\n";
echo "□ Usuário criado com sucesso\n";
echo "□ Permissões configuradas\n";
echo "□ API User criado\n";
echo "□ API Key configurada\n";
echo "□ Teste de login funcionando\n";
echo "□ Teste de conectividade funcionando\n";
echo "□ Criação de Lead funcionando\n";
echo "□ Criação de Opportunity funcionando\n\n";

echo "🎉 CONFIGURAÇÃO CONCLUÍDA!\n";
echo "===========================\n";
echo "O EspoCRM de desenvolvimento estará pronto para uso pelos webhooks.\n";
