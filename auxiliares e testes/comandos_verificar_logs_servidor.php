<?php
echo "=== COMANDOS PARA VERIFICAR LOGS DO SERVIDOR ===\n\n";

echo "🔍 COMANDOS PARA EXECUTAR NO SERVIDOR:\n\n";

echo "1️⃣ CONECTAR AO SERVIDOR:\n";
echo "   ssh mdmidac@mdmidia.com.br\n\n";

echo "2️⃣ ENTRAR NO CONTAINER DO ESPOCRM:\n";
echo "   docker exec -it espocrm bash\n\n";

echo "3️⃣ VERIFICAR LOGS RECENTES DO ESPOCRM:\n";
echo "   tail -50 /var/www/html/data/logs/espo-2025-10-15.log\n\n";

echo "4️⃣ PROCURAR POR WORKFLOW NO LOG:\n";
echo "   grep -i 'workflow' /var/www/html/data/logs/espo-2025-10-15.log | tail -20\n\n";

echo "5️⃣ PROCURAR POR ERROS NO LOG:\n";
echo "   grep -i 'error\\|exception\\|fatal' /var/www/html/data/logs/espo-2025-10-15.log | tail -20\n\n";

echo "6️⃣ PROCURAR POR LEADS CRIADOS HOJE:\n";
echo "   grep -i 'lead.*created\\|lead.*novo teste' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "7️⃣ PROCURAR POR OPORTUNIDADES CRIADAS HOJE:\n";
echo "   grep -i 'opportunity.*created\\|oportunidade.*criada' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "8️⃣ VERIFICAR LOGS DE WORKFLOW ESPECÍFICO:\n";
echo "   grep -i 'lead para oportunidade\\|lead to opportunity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "9️⃣ VERIFICAR LOGS DE VALIDAÇÃO:\n";
echo "   grep -i 'validation\\|valid' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "🔟 VERIFICAR LOGS DE PERMISSÕES:\n";
echo "   grep -i 'permission\\|access denied\\|forbidden' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣1️⃣ VERIFICAR LOGS DE CAMPOS OBRIGATÓRIOS:\n";
echo "   grep -i 'required\\|mandatory\\|assignedUser' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣2️⃣ VERIFICAR LOGS DE CRIAÇÃO DE REGISTROS:\n";
echo "   grep -i 'create.*record\\|POST.*Lead\\|POST.*Opportunity' /var/www/html/data/logs/espo-2025-10-15.log | tail -10\n\n";

echo "1️⃣3️⃣ VERIFICAR LOGS DE DAEMON (SE HOUVER):\n";
echo "   tail -20 /var/www/html/data/logs/daemon.log\n\n";

echo "1️⃣4️⃣ VERIFICAR LOGS DE WEBSOCKET (SE HOUVER):\n";
echo "   tail -20 /var/www/html/data/logs/websocket.log\n\n";

echo "1️⃣5️⃣ VERIFICAR LOGS DE CRON (SE HOUVER):\n";
echo "   tail -20 /var/www/html/data/logs/cron.log\n\n";

echo "1️⃣6️⃣ VERIFICAR LOGS DE PHP:\n";
echo "   tail -20 /var/log/php8.3-fpm.log\n\n";

echo "1️⃣7️⃣ VERIFICAR LOGS DO NGINX:\n";
echo "   sudo tail -20 /var/log/nginx/flyingdonkeys.error.log\n\n";

echo "1️⃣8️⃣ VERIFICAR LOGS DE SISTEMA:\n";
echo "   sudo tail -20 /var/log/syslog | grep -i espo\n\n";

echo "1️⃣9️⃣ VERIFICAR LOGS DE DOCKER:\n";
echo "   docker logs espocrm --tail 50\n\n";

echo "2️⃣0️⃣ VERIFICAR LOGS DE TODOS OS CONTAINERS:\n";
echo "   docker logs espocrm-nginx --tail 20\n";
echo "   docker logs espocrm --tail 20\n";
echo "   docker logs espocrm-websocket --tail 20\n";
echo "   docker logs espocrm-daemon --tail 20\n\n";

echo "🎯 COMANDO MAIS IMPORTANTE:\n";
echo "   grep -A 5 -B 5 'NOVO TESTE SILVA SERVIDOR' /var/www/html/data/logs/espo-2025-10-15.log\n\n";

echo "🔍 COMANDO PARA VERIFICAR WORKFLOW ESPECÍFICO:\n";
echo "   grep -A 10 -B 5 'workflow.*lead.*opportunity' /var/www/html/data/logs/espo-2025-10-15.log\n\n";

echo "⚠️ COMANDO PARA VERIFICAR ERROS CRÍTICOS:\n";
echo "   grep -i 'fatal\\|error\\|exception' /var/www/html/data/logs/espo-2025-10-15.log | grep -i 'workflow\\|lead\\|opportunity' | tail -20\n\n";

echo "✅ COMANDOS PARA VERIFICAÇÃO COMPLETA!\n";
