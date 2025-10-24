# 🚀 DEPLOY DO SIMULADOR OCTADESK

## 📋 INSTRUÇÕES DE DEPLOY

### **1️⃣ MÉTODO 1: Upload Manual**

1. **Acesse o servidor** via FTP/SFTP ou painel de controle
2. **Navegue** até o diretório raiz do site (`/public_html/` ou similar)
3. **Crie** o diretório: `/dev/octadesk-simulator/`
4. **Faça upload** dos arquivos:
   - `dev/octadesk-simulator/index.php`
   - `dev/octadesk-simulator/monitor.html`
   - `dev/octadesk-simulator/.htaccess`
5. **Crie** os diretórios:
   - `/dev/octadesk-simulator/data/`
   - `/dev/logs/`

### **2️⃣ MÉTODO 2: Script Automatizado**

1. **Faça upload** do arquivo `deploy_octadesk_server.php` para o diretório raiz
2. **Execute** via navegador: `https://bpsegurosimediato.com.br/deploy_octadesk_server.php`
3. **Siga** as instruções do script

### **3️⃣ MÉTODO 3: Via Terminal SSH**

```bash
# Navegar para o diretório raiz
cd /path/to/website/root

# Criar diretórios
mkdir -p dev/octadesk-simulator/data
mkdir -p dev/logs

# Fazer upload dos arquivos (via SCP/SFTP)
# index.php, monitor.html, .htaccess

# Configurar permissões
chmod 755 dev/octadesk-simulator
chmod 755 dev/octadesk-simulator/data
chmod 755 dev/logs
```

## 🧪 TESTE APÓS DEPLOY

### **1️⃣ Interface de Monitoramento**

```
https://bpsegurosimediato.com.br/dev/octadesk-simulator/monitor.html
```

### **2️⃣ Health Check**

```bash
curl -H "X-Api-Key: dev_octadesk_key_12345" \
     https://bpsegurosimediato.com.br/dev/octadesk-simulator/api/v1/health
```

### **3️⃣ Teste Completo**

```bash
php dev/scripts/test_octadesk_simulator.php
```

## 🔧 CONFIGURAÇÕES

### **API Keys Válidas:**

- `dev_octadesk_key_12345`
- `test_octadesk_key_67890`
- `simulator_octadesk_key`

### **Endpoints Disponíveis:**

- `POST /api/v1/contacts` - Criar contato
- `GET /api/v1/contacts` - Listar contatos
- `POST /api/v1/conversations` - Criar conversa
- `GET /api/v1/conversations` - Listar conversas
- `POST /api/v1/messages` - Enviar mensagem
- `GET /api/v1/health` - Health check
- `GET /api/v1/info` - Informações do simulador

## 📊 MONITORAMENTO

### **Logs do Simulador:**

- Arquivo: `/dev/logs/octadesk_simulator.txt`

### **Logs do Webhook:**

- Arquivo: `/dev/logs/webhook_octadesk_dev.txt`

### **Dados do Simulador:**

- Arquivo: `/dev/octadesk-simulator/data/simulator_data.json`

## 🚨 SOLUÇÃO DE PROBLEMAS

### **Erro 404:**

- Verificar se os arquivos foram uploadados corretamente
- Verificar se o `.htaccess` está presente
- Verificar permissões dos diretórios

### **Erro 500:**

- Verificar logs de erro do servidor
- Verificar permissões dos arquivos
- Verificar se o PHP está funcionando

### **Erro de API Key:**

- Verificar se está usando uma das API keys válidas
- Verificar se o header `X-Api-Key` está sendo enviado

## ✅ CHECKLIST DE DEPLOY

- [ ] Arquivos uploadados (`index.php`, `monitor.html`, `.htaccess`)
- [ ] Diretórios criados (`data/`, `logs/`)
- [ ] Permissões configuradas (755)
- [ ] Interface de monitoramento acessível
- [ ] Health check funcionando
- [ ] Logs sendo gerados
- [ ] Teste completo executado com sucesso
