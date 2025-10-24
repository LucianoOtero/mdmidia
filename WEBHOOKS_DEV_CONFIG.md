# 🔧 CONFIGURAÇÕES DOS WEBHOOKS DE DESENVOLVIMENTO

## 📋 RESUMO DAS CONFIGURAÇÕES

### **🎯 add_travelangels_dev.php**

- **Destino:** EspoCRM de Desenvolvimento
- **URL:** `https://dev.flyingdonkeys.com.br`
- **API Key:** Carregada de `espocrm_dev_credentials.php`
- **Webhook URL:** `https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php`
- **Secret Webflow:** `888931809d5215258729a8df0b503403bfd300f32ead1a983d95a6119b166142`

### **🎭 add_webflow_octa_dev.php**

- **Destino:** Simulador OctaDesk
- **URL:** `https://bpsegurosimediato.com.br/dev/octadesk-simulator`
- **API Key:** `dev_octadesk_key_12345`
- **Webhook URL:** `https://bpsegurosimediato.com.br/dev/webhooks/add_webflow_octa_dev.php`
- **Secret Webflow:** `1dead60b2edf3bab32d8084b6ee105a9458c5cfe282e7b9d27e908f5a6c40291`

## 🔗 CONFIGURAÇÃO NO WEBFLOW

### **Para TravelAngels (EspoCRM):**

```
Webhook URL: https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php
Secret: 888931809d5215258729a8df0b503403bfd300f32ead1a983d95a6119b166142
```

### **Para OctaDesk (Simulador):**

```
Webhook URL: https://bpsegurosimediato.com.br/dev/webhooks/add_webflow_octa_dev.php
Secret: 1dead60b2edf3bab32d8084b6ee105a9458c5cfe282e7b9d27e908f5a6c40291
```

## 🧪 TESTES

### **Teste TravelAngels:**

```bash
curl -X POST https://bpsegurosimediato.com.br/dev/webhooks/add_travelangels_dev.php \
  -H "Content-Type: application/json" \
  -H "X-Webflow-Signature: sha256=..." \
  -d '{"test": "data"}'
```

### **Teste OctaDesk:**

```bash
curl -X POST https://bpsegurosimediato.com.br/dev/webhooks/add_webflow_octa_dev.php \
  -H "Content-Type: application/json" \
  -H "X-Webflow-Signature: sha256=..." \
  -d '{"test": "data"}'
```

## 📊 MONITORAMENTO

### **Logs TravelAngels:**

- Arquivo: `dev/logs/travelangels_dev.txt`
- EspoCRM: `dev.flyingdonkeys.com.br`

### **Logs OctaDesk:**

- Arquivo: `dev/logs/webhook_octadesk_dev.txt`
- Simulador: `https://bpsegurosimediato.com.br/dev/octadesk-simulator/monitor.html`

## ✅ STATUS

- ✅ **add_travelangels_dev.php** → `dev.flyingdonkeys.com.br` (EspoCRM)
- ✅ **add_webflow_octa_dev.php** → `octadesk-simulator` (Simulador)
- ✅ **Configurações** atualizadas
- ✅ **URLs** corretas
- ✅ **API Keys** configuradas
- ✅ **Logs** funcionando
