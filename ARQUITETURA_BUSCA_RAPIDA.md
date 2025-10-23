# ARQUITETURA DETALHADA - SISTEMA DE BUSCA RÁPIDA

## 🎯 **OBJETIVO**
Este arquivo serve como **base de referência rápida** para todas as buscas e verificações do projeto, evitando demoras desnecessárias.

## 📁 **MAPEAMENTO COMPLETO DE ARQUIVOS**

### **🔍 WEBHOOKS DE DESENVOLVIMENTO**
```
SERVIDOR: /var/www/html/dev/webhooks/
├── add_travelangels.php          # ✅ API V2 - TravelAngels Dev
├── add_webflow_octa.php          # ✅ API V2 - OctaDesk Dev
└── health.php                    # ✅ Health Check Dev

LOCAL: mdmidia/dev/webhooks/
├── add_travelangels_dev.php      # ✅ Versão local corrigida
└── add_webflow_octa_dev.php      # ✅ Versão local corrigida
```

### **🔍 WEBHOOKS DE PRODUÇÃO**
```
SERVIDOR: /var/www/html/
├── add_travelangels.php          # ✅ Produção - TravelAngels
├── add_collect_chat.php          # ✅ Produção - Collect Chat
└── add_webflow_octa.php          # ✅ Produção - OctaDesk

LOCAL: mdmidia/production/webhooks/
├── add_travelangels.php          # ✅ Versão local produção
├── add_collect_chat.php          # ✅ Versão local produção
└── add_webflow_octa.php          # ✅ Versão local produção
```

### **🔍 ARQUIVOS DE LOG - MAPEAMENTO DIRETO**

#### **DESENVOLVIMENTO**
```
/var/www/html/dev/logs/
├── travelangels_dev.txt          # 🔍 LOG PRINCIPAL TravelAngels Dev
├── octadesk_dev.txt              # 🔍 LOG PRINCIPAL OctaDesk Dev
├── general_dev.txt               # 🔍 LOG GERAL Desenvolvimento
└── errors_dev.txt                # 🔍 LOG ERROS Desenvolvimento
```

#### **PRODUÇÃO**
```
/var/www/html/
├── logs_travelangels.txt         # 🔍 LOG PRINCIPAL TravelAngels Prod
├── collect_chat_logs.txt         # 🔍 LOG PRINCIPAL Collect Chat Prod
└── octa_webflow_webhook.log      # 🔍 LOG PRINCIPAL OctaDesk Prod
```

## 🔑 **CONFIGURAÇÕES CRÍTICAS**

### **SECRET KEYS WEBFLOW**
```php
// DESENVOLVIMENTO
'travelangels' => '888931809d5215258729a8df0b503403bfd300f32ead1a983d95a6119b166142'
'octadesk' => '1dead60b2edf3bab32d8084b6ee105a9458c5cfe282e7b9d27e908f5a6c40291'

// PRODUÇÃO (mesmas keys)
'travelangels' => '1601e39b8b4940a5ac7a49b80c8f05571ef6d408908b76b76d314dcdbe061a45'
'octadesk' => 'eabd63aba74686e94c55c5d678699ae29135962d1cc01569b25dbbd2274314a3'
```

### **URLS WEBHOOKS**
```php
// DESENVOLVIMENTO
'https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels.php'
'https://bpsegurosimediato.com.br/dev/webhooks/add_webflow_octa.php'

// PRODUÇÃO
'https://bpsegurosimediato.com.br/add_travelangels.php'
'https://bpsegurosimediato.com.br/add_collect_chat.php'
'https://bpsegurosimediato.com.br/add_webflow_octa.php'
```

## 🎯 **PROTOCOLO DE BUSCA RÁPIDA**

### **1. VERIFICAR LOGS DE DESENVOLVIMENTO**
```bash
# SEMPRE verificar primeiro:
ssh root@bpsegurosimediato.com.br "tail -20 /var/www/html/dev/logs/travelangels_dev.txt"
ssh root@bpsegurosimediato.com.br "tail -20 /var/www/html/dev/logs/octadesk_dev.txt"
```

### **2. VERIFICAR LOGS DE PRODUÇÃO**
```bash
# Se não encontrar em dev, verificar produção:
ssh root@bpsegurosimediato.com.br "tail -20 /var/www/html/logs_travelangels.txt"
ssh root@bpsegurosimediato.com.br "tail -20 /var/www/html/collect_chat_logs.txt"
```

### **3. BUSCAR DADOS ESPECÍFICOS**
```bash
# Para dados específicos do teste:
ssh root@bpsegurosimediato.com.br "grep -r 'LUCIANO\|lrotero\|FPG-8D63' /var/www/html/dev/logs/"
ssh root@bpsegurosimediato.com.br "grep -r 'LUCIANO\|lrotero\|FPG-8D63' /var/www/html/"
```

## 🔧 **CORREÇÕES IMPLEMENTADAS**

### **CRÍTICA: Campo leadSource**
```php
// ❌ ANTES (INCORRETO)
'source' => $source  // Para Opportunity

// ✅ DEPOIS (CORRETO)
'leadSource' => $source  // Para Opportunity
'source' => $source       // Para Lead (mantido)
```

### **ARQUIVOS CORRIGIDOS**
- ✅ `add_travelangels_dev.php` (local)
- ✅ `add_travelangels.php` (servidor dev)
- ✅ `add_travelangels.php` (produção)
- ✅ `add_collect_chat.php` (produção)

## 📊 **STATUS ATUAL**

### **✅ FUNCIONANDO**
- **Desenvolvimento**: API V2 implementada e funcionando
- **Produção**: Todos os webhooks funcionando
- **Logs**: Sensibilizando corretamente
- **Correção leadSource**: Aplicada e validada

### **🎯 TESTE MAIS RECENTE**
**Dados**: LUCIANO RODRIGUES OTERO, lrotero@gmail.com, FPG-8D63
**Status**: ✅ Processado com sucesso em ambos os ambientes
**Logs**: ✅ Sensibilizando corretamente

## 🚀 **COMANDOS DE VERIFICAÇÃO RÁPIDA**

### **VERIFICAR STATUS GERAL**
```bash
# Verificar se webhooks estão funcionando
ssh root@bpsegurosimediato.com.br "curl -X POST https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels.php -H 'Content-Type: application/json' -d '{}'"
```

### **VERIFICAR LOGS MAIS RECENTES**
```bash
# Desenvolvimento
ssh root@bpsegurosimediato.com.br "ls -la /var/www/html/dev/logs/"

# Produção  
ssh root@bpsegurosimediato.com.br "ls -la /var/www/html/*.txt /var/www/html/*.log"
```

### **VERIFICAR SINTAXE PHP**
```bash
ssh root@bpsegurosimediato.com.br "php -l /var/www/html/dev/webhooks/add_travelangels.php"
ssh root@bpsegurosimediato.com.br "php -l /var/www/html/dev/webhooks/add_webflow_octa.php"
```

## 📋 **CHECKLIST DE VERIFICAÇÃO**

### **ANTES DE QUALQUER BUSCA**
1. ✅ Identificar se é desenvolvimento ou produção
2. ✅ Usar o arquivo de log correto
3. ✅ Verificar timestamp mais recente
4. ✅ Buscar dados específicos do teste

### **APÓS QUALQUER TESTE**
1. ✅ Verificar logs de desenvolvimento primeiro
2. ✅ Se não encontrar, verificar produção
3. ✅ Confirmar processamento correto
4. ✅ Validar campo leadSource se aplicável

---
**REGRA DE OURO**: Sempre consultar este arquivo antes de fazer buscas para evitar demoras desnecessárias.


