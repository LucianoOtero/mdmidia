<?php
echo "=== COMANDOS PARA VERIFICAR LOGS DO WORKFLOW 'LEAD PARA OPORTUNIDADE' ===\n\n";

echo "🔍 COMANDOS PARA EXECUTAR NO SERVIDOR FLYINGDONKEYS:\n\n";

echo "1️⃣ CONECTAR AO SERVIDOR:\n";
echo "   ssh mdmidac@mdmidia.com.br\n\n";

echo "2️⃣ ENTRAR NO CONTAINER DO ESPOCRM:\n";
echo "   docker exec -it espocrm bash\n\n";

echo "3️⃣ VERIFICAR LOGS DE SUCESSO DO WORKFLOW:\n";
echo "   grep -i 'workflow.*success\\|workflow.*completed\\|workflow.*finished' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "4️⃣ VERIFICAR LOGS DE ERRO DO WORKFLOW:\n";
echo "   grep -i 'workflow.*error\\|workflow.*exception\\|workflow.*failed' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "5️⃣ VERIFICAR LOGS DE EXECUÇÃO DO WORKFLOW:\n";
echo "   grep -i 'workflow.*execution\\|workflow.*executed\\|workflow.*started' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "6️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE LEAD:\n";
echo "   grep -i 'workflow.*lead.*afterRecordCreated' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "7️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE OPORTUNIDADE:\n";
echo "   grep -i 'workflow.*opportunity\\|workflow.*oportunidade' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "8️⃣ VERIFICAR LOGS DE WORKFLOW POR AÇÃO CREATE RECORD:\n";
echo "   grep -i 'workflow.*createRecord\\|workflow.*create.*record' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "9️⃣ VERIFICAR LOGS DE WORKFLOW POR CONDIÇÕES:\n";
echo "   grep -i 'workflow.*condition\\|workflow.*condition.*failed\\|workflow.*condition.*passed' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "🔟 VERIFICAR LOGS DE WORKFLOW POR AÇÕES:\n";
echo "   grep -i 'workflow.*action\\|workflow.*action.*failed\\|workflow.*action.*success' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣1️⃣ VERIFICAR LOGS DE WORKFLOW POR TRIGGER:\n";
echo "   grep -i 'workflow.*trigger\\|workflow.*afterRecordCreated' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣2️⃣ VERIFICAR LOGS DE WORKFLOW POR STATUS:\n";
echo "   grep -i 'workflow.*status\\|workflow.*active\\|workflow.*inactive' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣3️⃣ VERIFICAR LOGS DE WORKFLOW POR FINALIZAÇÃO:\n";
echo "   grep -i 'workflow.*end\\|workflow.*finished\\|workflow.*completed' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣4️⃣ VERIFICAR LOGS DE WORKFLOW POR FALHA:\n";
echo "   grep -i 'workflow.*failed\\|workflow.*failure\\|workflow.*abort' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣5️⃣ VERIFICAR LOGS DE WORKFLOW POR TIMEOUT:\n";
echo "   grep -i 'workflow.*timeout\\|workflow.*time.*out' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣6️⃣ VERIFICAR LOGS DE WORKFLOW POR MEMÓRIA:\n";
echo "   grep -i 'workflow.*memory\\|workflow.*memory.*limit' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣7️⃣ VERIFICAR LOGS DE WORKFLOW POR PERMISSÕES:\n";
echo "   grep -i 'workflow.*permission\\|workflow.*access.*denied' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣8️⃣ VERIFICAR LOGS DE WORKFLOW POR VALIDAÇÃO:\n";
echo "   grep -i 'workflow.*validation\\|workflow.*field.*validation' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣9️⃣ VERIFICAR LOGS DE WORKFLOW POR CAMPOS OBRIGATÓRIOS:\n";
echo "   grep -i 'workflow.*required\\|workflow.*mandatory\\|workflow.*assignedUser' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "2️⃣0️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE LEAD:\n";
echo "   grep -i 'workflow.*lead.*entity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "2️⃣1️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE OPORTUNIDADE:\n";
echo "   grep -i 'workflow.*opportunity.*entity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "2️⃣2️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE LEAD:\n";
echo "   grep -i 'workflow.*lead.*entity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "2️⃣3️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE OPORTUNIDADE:\n";
echo "   grep -i 'workflow.*opportunity.*entity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "2️⃣4️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE LEAD:\n";
echo "   grep -i 'workflow.*lead.*entity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "2️⃣5️⃣ VERIFICAR LOGS DE WORKFLOW POR ENTIDADE OPORTUNIDADE:\n";
echo "   grep -i 'workflow.*opportunity.*entity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "🎯 COMANDO MAIS IMPORTANTE - VERIFICAR TODOS OS LOGS DE WORKFLOW:\n";
echo "   grep -i 'workflow' /var/www/html/data/logs/espo-2025-10-15.log | tail -20\n\n";

echo "🔍 COMANDO PARA VERIFICAR LOGS ESPECÍFICOS DO LEAD 'NOVO TESTE SILVA SERVIDOR':\n";
echo "   grep -A 10 -B 10 'NOVO TESTE SILVA SERVIDOR' /var/www/html/data/logs/espo-2025-10-15.log\n\n";

echo "⚠️ COMANDO PARA VERIFICAR LOGS DE WORKFLOW POR ID (se conhecido):\n";
echo "   grep -i 'workflow.*[ID_DO_WORKFLOW]' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "✅ COMANDOS PARA VERIFICAÇÃO COMPLETA DO WORKFLOW!\n";
