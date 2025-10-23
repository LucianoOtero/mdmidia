# TESTE DO ENDPOINT add_collect_chat.php NO MDMIDIA

## 📋 DESCRIÇÃO

Programas de teste para verificar o funcionamento do endpoint `add_collect_chat.php` no servidor mdmidia.com.br.

## 📁 ARQUIVOS CRIADOS

### 1. `test_add_collect_chat_mdmidia.php`
- **Tipo**: Script PHP completo
- **Funcionalidade**: Teste detalhado com análise completa
- **Recursos**: 
  - Dados fictícios para teste
  - Análise de conectividade
  - Medição de tempo de execução
  - Análise da resposta JSON
  - Informações detalhadas de debug

### 2. `test_add_collect_chat_mdmidia.ps1`
- **Tipo**: Script PowerShell
- **Funcionalidade**: Teste rápido e simples
- **Recursos**:
  - Interface colorida
  - Execução rápida
  - Análise básica da resposta

## 🚀 COMO USAR

### Opção 1: PHP (Recomendado)
```bash
php test_add_collect_chat_mdmidia.php
```

### Opção 2: PowerShell
```powershell
.\test_add_collect_chat_mdmidia.ps1
```

## 📊 DADOS DE TESTE

Os programas utilizam os seguintes dados fictícios:

```json
{
    "NAME": "TESTE AUTOMATIZADO - 2025-10-21 14:51:32",
    "NUMBER": "11999887766",
    "CPF": "12345678901",
    "CEP": "01234567",
    "PLACA": "ABC1234",
    "EMAIL": "teste@exemplo.com",
    "gclid": "test_gclid_1737472292"
}
```

## 🔍 O QUE É TESTADO

### ✅ Conectividade
- Conexão com o servidor mdmidia.com.br
- Tempo de resposta
- Status HTTP

### ✅ Processamento
- Envio de dados JSON
- Validação da placa
- Criação do Lead no TravelAngels
- Criação da Oportunidade no FlyingDonkeys

### ✅ Logs
- Verificação de logs gerados
- Rastreamento do processamento

## 📋 PRÓXIMOS PASSOS APÓS O TESTE

### 1. Verificar Logs no Servidor
- **Arquivo**: `collect_chat_logs.txt`
- **Localização**: `/var/www/html/` no servidor mdmidia
- **Buscar por**: Nome do teste usado

### 2. Verificar TravelAngels
- **URL**: https://travelangels.com.br
- **Buscar por**: Email `teste@exemplo.com`
- **Verificar**: Lead criado com origem "Collect Chat"

### 3. Verificar FlyingDonkeys
- **URL**: https://flyingdonkeys.com.br
- **Buscar por**: Email `teste@exemplo.com`
- **Verificar**: 
  - Lead criado com origem "Collect Chat"
  - Oportunidade criada com origem "Collect Chat"

### 4. Verificar Campos de Origem
- **Lead**: Campo `source` = "Collect Chat"
- **Oportunidade**: Campo `leadSource` = "Collect Chat"

## 🎯 RESULTADOS ESPERADOS

### ✅ Sucesso
```json
{
    "status": "success",
    "message": "Lead processado no TravelAngels e FlyingDonkeys com sucesso",
    "leadIdTravelAngels": "64f8a1b2c3d4e5f6",
    "leadIdFlyingDonkeys": "64f8a1b2c3d4e5f7"
}
```

### ❌ Possíveis Erros
- **Timeout**: Servidor não responde
- **404**: Endpoint não encontrado
- **500**: Erro interno do servidor
- **JSON Inválido**: Resposta malformada

## 🔧 TROUBLESHOOTING

### Problema: Timeout
- Verificar conectividade com mdmidia.com.br
- Verificar se o servidor está online
- Aumentar timeout no script

### Problema: 404 Not Found
- Verificar se o arquivo existe no servidor
- Verificar permissões do arquivo
- Verificar configuração do servidor web

### Problema: 500 Internal Server Error
- Verificar logs do servidor
- Verificar dependências PHP
- Verificar configuração do EspoCRM

## 📝 NOTAS IMPORTANTES

- Os dados são fictícios e não devem ser usados em produção
- O teste cria registros reais nos sistemas EspoCRM
- Verificar sempre os logs após o teste
- Limpar dados de teste se necessário

## 🆘 SUPORTE

Em caso de problemas:
1. Verificar logs do servidor
2. Verificar conectividade
3. Verificar configuração do endpoint
4. Contatar administrador do sistema
