# ESTRUTURA DE DESENVOLVIMENTO - MDMIDIA

## 📁 Organização dos Diretórios

### 🏗️ **ESTRUTURA IMPLEMENTADA:**

```
mdmidia/
├── dev/                          # Ambiente de desenvolvimento
│   ├── webhooks/
│   │   └── add_travelangels_dev.php    # ✅ CORRIGIDO - usa leadSource para Opportunity
│   ├── config/
│   │   └── dev_config.php              # Configurações de desenvolvimento
│   └── logs/                           # Logs de desenvolvimento
├── production/                   # Ambiente de produção
│   ├── webhooks/
│   │   ├── add_travelangels.php        # Webhook principal TravelAngels
│   │   ├── add_collect_chat.php        # Webhook Collect Chat
│   │   ├── add_webflow_octa.php        # Webhook OctaDesk
│   │   ├── class.php                   # Classe do CRM
│   │   ├── cpf-validate.php            # Validação CPF
│   │   └── placa-validate.php           # Validação Placa
│   └── logs/                           # Logs de produção
├── tests/                        # Testes
│   ├── scripts/
│   │   └── test_travelangels_dev.php   # Teste do webhook de desenvolvimento
│   └── data/                           # Dados de teste
└── docs/                         # Documentação
    └── analysis/                       # Análises e relatórios
```

## 🔧 **CORREÇÕES IMPLEMENTADAS:**

### ✅ **add_travelangels_dev.php - CORRIGIDO:**

#### **Lead (CORRETO):**
```php
$lead_data = [
    'firstName' => $data['name'] ?? 'Nome não informado',
    'emailAddress' => $data['email'] ?? '',
    'phoneNumber' => $data['phone'] ?? '',
    'source' => 'Webflow Dev', // ✅ CORRETO para Lead
    'description' => 'Lead enviado do ambiente de desenvolvimento'
];
```

#### **Opportunity (CORRIGIDO):**
```php
$opportunityPayload = [
    'name' => $data['name'] ?? 'Nome não informado',
    'leadId' => $response['id'] ?? 'unknown',
    'stage' => 'Novo Sem Contato',
    'amount' => 0,
    'probability' => 10,
    'leadSource' => 'Webflow Dev', // ✅ CORRETO para Opportunity (não 'source')
    'description' => 'Oportunidade criada no ambiente de desenvolvimento'
];
```

## 🎯 **CONFIGURAÇÕES DE DESENVOLVIMENTO:**

### **📄 dev_config.php:**
- **Ambiente**: `development`
- **Debug**: Ativado
- **Logging**: Detalhado
- **API V2**: Validação de signature
- **Modo Teste**: Simulação de CRM

### **🔑 Secret Keys (Reais):**
- **TravelAngels**: `888931809d5215258729a8df0b503403bfd300f32ead1a983d95a6119b166142`
- **OctaDesk**: `1dead60b2edf3bab32d8084b6ee105a9458c5cfe282e7b9d27e908f5a6c40291`
- **Collect Chat**: `1601e39b8b4940a5ac7a49b80c8f05571ef6d408908b76b76d314dcdbe061a45`

## 🧪 **TESTES DISPONÍVEIS:**

### **📋 test_travelangels_dev.php:**
- Testa webhook de desenvolvimento local
- Testa webhook de desenvolvimento no servidor
- Verifica campos `source` e `leadSource`
- Valida respostas e logs

## 🚀 **PRÓXIMOS PASSOS:**

1. **✅ Estrutura organizada implementada**
2. **✅ Correção leadSource aplicada**
3. **🔄 Testar webhook de desenvolvimento**
4. **🔄 Implementar add_webflow_octa_dev.php**
5. **🔄 Implementar add_collect_chat_dev.php**
6. **🔄 Migrar para produção após validação**

## 📊 **STATUS ATUAL:**

- **✅ Estrutura de diretórios**: Implementada
- **✅ Configuração de desenvolvimento**: Implementada
- **✅ Webhook TravelAngels V2**: Corrigido e implementado
- **✅ Sistema de testes**: Implementado
- **✅ Logging detalhado**: Implementado
- **✅ Validação API V2**: Implementada

## 🎉 **RESULTADO:**

**A estrutura de desenvolvimento está organizada e o webhook TravelAngels V2 está corrigido para usar `leadSource` na Opportunity!**


