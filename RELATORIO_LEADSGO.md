# Relatório Técnico - Integração LeadsGo com EspoCRM

**Data:** 09/10/2025  
**Endpoint:** `https://mdmidia.com.br/add_leadsgo.php`  
**Status:** ✅ Funcionando corretamente  

## 📊 Análise dos Logs

### ✅ Teste Bem-Sucedido
```
[2025-10-08 22:42:11] Dados recebidos via POST
[2025-10-08 22:42:11] Recebido do LeadsGo: {"data":{"NOME":"Teste","DDD-CELULAR":"011","CELULAR":"987654321","Email":"teste@email.com","CEP":"01234-567","CPF":"123.456.789-00","MARCA":"Toyota","PLACA":"ABC1234","ANO":"2020","GCLID_FLD":"test_gclid_123"},"d":"2024-01-15 10:30:00","name":"leadsgo.online"}
[2025-10-08 22:42:11] Lead criado com sucesso no EspoCRM (ID: 68e6e8c2e39c0ecca)
```

### ❌ Problemas Identificados

1. **Chamadas Vazias (28 ocorrências)**
   - Requisições sem dados válidos
   - Indica chamadas GET ou POST sem payload

2. **Método HTTP Incorreto**
   ```
   [2025-10-09 10:07:08] Método HTTP: GET
   [2025-10-09 10:07:08] ERRO: Método incorreto. Esperado: POST, Recebido: GET
   ```

## 🔧 Especificações Técnicas

### Formato de Dados Aceito
```json
{
  "data": {
    "NOME": "Nome do Cliente",
    "DDD-CELULAR": "011",
    "CELULAR": "987654321",
    "Email": "cliente@email.com",
    "CEP": "01234-567",
    "CPF": "123.456.789-00",
    "MARCA": "Toyota",
    "PLACA": "ABC1234",
    "ANO": "2020",
    "GCLID_FLD": "gclid_value"
  },
  "d": "2024-01-15 10:30:00",
  "name": "leadsgo.online"
}
```

### Configuração da Requisição
- **Método:** POST
- **Content-Type:** `application/json`
- **URL:** `https://mdmidia.com.br/add_leadsgo.php`
- **Timeout:** Recomendado 30 segundos

### Campos Obrigatórios
- `data.NOME` - Nome do cliente
- `data.Email` - Email do cliente
- `data.CELULAR` - Telefone celular
- `data.DDD-CELULAR` - DDD do celular

### Campos Opcionais
- `data.CEP` - CEP
- `data.CPF` - CPF
- `data.MARCA` - Marca do veículo
- `data.PLACA` - Placa do veículo
- `data.ANO` - Ano do veículo
- `data.GCLID_FLD` - Google Click ID
- `d` - Data/hora do lead
- `name` - Origem do lead

## 🚨 Problemas a Corrigir

### 1. Método HTTP
**Problema:** Chamadas usando GET em vez de POST  
**Solução:** Alterar todas as chamadas para POST

### 2. Dados Vazios
**Problema:** 28 chamadas sem dados válidos  
**Solução:** Verificar se os dados estão sendo enviados corretamente

### 3. Content-Type
**Problema:** Pode não estar definindo `application/json`  
**Solução:** Definir header `Content-Type: application/json`

## 📝 Exemplo de Código Correto

### JavaScript/Node.js
```javascript
const axios = require('axios');

const sendLead = async (leadData) => {
  try {
    const response = await axios.post('https://mdmidia.com.br/add_leadsgo.php', {
      data: {
        NOME: leadData.nome,
        DDD_CELULAR: leadData.ddd,
        CELULAR: leadData.celular,
        Email: leadData.email,
        CEP: leadData.cep,
        CPF: leadData.cpf,
        MARCA: leadData.marca,
        PLACA: leadData.placa,
        ANO: leadData.ano,
        GCLID_FLD: leadData.gclid
      },
      d: new Date().toISOString(),
      name: 'leadsgo.online'
    }, {
      headers: {
        'Content-Type': 'application/json'
      },
      timeout: 30000
    });
    
    console.log('Lead enviado com sucesso:', response.data);
  } catch (error) {
    console.error('Erro ao enviar lead:', error.response?.data || error.message);
  }
};
```

### PHP
```php
<?php
$leadData = [
    'data' => [
        'NOME' => 'Nome do Cliente',
        'DDD-CELULAR' => '011',
        'CELULAR' => '987654321',
        'Email' => 'cliente@email.com',
        'CEP' => '01234-567',
        'CPF' => '123.456.789-00',
        'MARCA' => 'Toyota',
        'PLACA' => 'ABC1234',
        'ANO' => '2020',
        'GCLID_FLD' => 'gclid_value'
    ],
    'd' => date('Y-m-d H:i:s'),
    'name' => 'leadsgo.online'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://mdmidia.com.br/add_leadsgo.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($leadData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($leadData))
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "Lead enviado com sucesso: " . $response;
} else {
    echo "Erro HTTP $httpCode: " . $response;
}
?>
```

## 🔍 Validações Implementadas

O endpoint possui as seguintes validações:

1. **Método HTTP:** Aceita apenas POST
2. **Dados Vazios:** Retorna erro 400 se não houver dados
3. **JSON Válido:** Valida formato JSON
4. **Logs Detalhados:** Registra todas as operações com timestamp
5. **Tratamento de DDD:** Remove primeiro dígito se DDD tiver 3 dígitos

## 📈 Resposta de Sucesso

```json
{
  "status": "success",
  "message": "Lead inserido com sucesso"
}
```

## ❌ Respostas de Erro

### Método Incorreto (405)
```json
{
  "status": "error",
  "message": "Method not allowed. Use POST method.",
  "received_method": "GET",
  "expected_methods": ["POST"]
}
```

### Dados Vazios (400)
```json
{
  "status": "error",
  "message": "No data received. Please send JSON data.",
  "method": "POST",
  "content_type": "application/json"
}
```

### JSON Inválido (400)
```json
{
  "status": "error",
  "message": "Invalid JSON format",
  "json_error": "Syntax error",
  "received_data": "invalid_json_string"
}
```

## 🎯 Próximos Passos

1. **Corrigir método HTTP:** Alterar de GET para POST
2. **Verificar dados:** Garantir que os dados estão sendo enviados
3. **Definir Content-Type:** Adicionar header `application/json`
4. **Testar novamente:** Enviar um lead de teste
5. **Monitorar logs:** Verificar se não há mais erros

## 📞 Suporte

Para dúvidas ou problemas, verificar:
- Logs do endpoint: `https://mdmidia.com.br/logs_leadsgo.txt`
- Status HTTP das respostas
- Formato dos dados enviados

---

**Status:** ✅ Endpoint funcionando corretamente  
**Ação Necessária:** 🔧 Corrigir chamadas do LeadsGo  
**Prioridade:** 🚨 Alta
