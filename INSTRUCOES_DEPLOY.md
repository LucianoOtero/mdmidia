# Instruções para Deploy do Endpoint LeadsGo

## Arquivos para Upload

### 1. Arquivo Principal
- **Nome:** `add_leadsgo.php`
- **Destino:** `/public_html/` ou `/www/` (raiz do domínio)
- **URL Final:** `https://mdmidia.com.br/add_leadsgo.php`

### 2. Arquivo de Teste (Opcional)
- **Nome:** `test_leadsgo.php`
- **Destino:** `/public_html/` ou `/www/`
- **URL Final:** `https://mdmidia.com.br/test_leadsgo.php`

## Pré-requisitos no Servidor

### 1. Arquivo class.php
Certifique-se de que o arquivo `class.php` já existe no servidor, pois o `add_leadsgo.php` depende dele:
```
https://mdmidia.com.br/class.php
```

### 2. Permissões de Escrita
O servidor precisa ter permissão para criar/escrever o arquivo de log:
```
logs_leadsgo.txt
```

## Configuração no LeadsGo

### Webhook URL
```
https://mdmidia.com.br/add_leadsgo.php
```

### Método
```
POST
```

### Content-Type
```
application/json
```

## Teste do Endpoint

### 1. Teste Manual
Acesse: `https://mdmidia.com.br/test_leadsgo.php`

### 2. Verificar Logs
Após o teste, verifique se o arquivo `logs_leadsgo.txt` foi criado e contém os dados.

## Estrutura de Dados Esperada

O endpoint espera receber dados no formato JSON com a seguinte estrutura:

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

## Monitoramento

- **Logs:** `logs_leadsgo.txt`
- **Status HTTP:** 200 (sucesso) ou erro
- **Resposta:** JSON com status e mensagem

## Troubleshooting

### Se o endpoint não funcionar:
1. Verifique se o arquivo `class.php` existe
2. Verifique as permissões de escrita
3. Consulte os logs de erro do servidor
4. Teste com o arquivo `test_leadsgo.php`

