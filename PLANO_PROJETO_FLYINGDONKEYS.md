# Plano de Projeto: Integração com FlyingDonkeys

## Objetivo
Modificar os arquivos `add_travelangels_v4.php` e `add_collect_chat_v4.php` para incluir os mesmos registros no FlyingDonkeys (endpoint `https://flyingdonkeys.com.br`) além do TravelAngels, utilizando as mesmas API keys e o mesmo formato.

## Análise Atual

### add_travelangels_v4.php
- **EspoCRM atual**: `https://travelangels.com.br`
- **API Key**: `7a6c08d438ee131971f561fd836b5e15`
- **Entidade**: `Lead`
- **Fonte**: Webflow

### add_collect_chat_v4.php
- **EspoCRM atual**: `https://travelangels.com.br`
- **API Key**: `d5bcb42f62d1d96f8090a1002b792335`
- **Entidade**: `Lead`
- **Fonte**: Collect.chat

## Plano de Execução

### 1. Análise da estrutura atual
- Verificar configuração atual do cliente EspoCRM
- Identificar localização das chamadas de API
- Confirmar estrutura do payload enviado

### 2. Implementação da duplicação
- Criar segundo cliente EspoCRM para FlyingDonkeys
- Manter cliente original para TravelAngels
- Duplicar chamadas de API para ambos os endpoints
- Usar as mesmas API keys em ambos os sistemas

### 3. Tratamento de erros
- Implementar try-catch para cada chamada
- Logs separados para cada sistema
- Continuar processamento mesmo se um sistema falhar

### 4. Testes
- Teste com dados simulados
- Validação em ambos os sistemas
- Verificação de logs

### 5. Deploy
- Upload dos arquivos modificados
- Teste em ambiente de produção
- Validação nos dois EspoCRMs

## Alterações Técnicas Necessárias

### Alteração 1: add_travelangels_v4.php
**Localização**: Após linha 10 (configuração do cliente)

**Adicionar**:
```php
// Cliente para FlyingDonkeys
$clientFlyingDonkeys = new EspoApiClient('https://flyingdonkeys.com.br');
$clientFlyingDonkeys->setApiKey('7a6c08d438ee131971f561fd836b5e15');
```

**Modificar chamada de API** (linha ~39):
```php
// Envio para TravelAngels
$responseTravelAngels = $client->request('POST', 'Lead', [
    'firstName' => $name,
    'emailAddress' => $email,
    'cCelular' => $cel,
    'addressPostalCode' => $cep,
    'cCpftext' => $cpf,
    'cMarca' => $marca,
    'cPlaca' => $placa,
    'cAnoMod' => $ano,
    'cGclid' => $gclid,
    'cWebpage' => $webpage,
]);

// Envio para FlyingDonkeys
$responseFlyingDonkeys = $clientFlyingDonkeys->request('POST', 'Lead', [
    'firstName' => $name,
    'emailAddress' => $email,
    'cCelular' => $cel,
    'addressPostalCode' => $cep,
    'cCpftext' => $cpf,
    'cMarca' => $marca,
    'cPlaca' => $placa,
    'cAnoMod' => $ano,
    'cGclid' => $gclid,
    'cWebpage' => $webpage,
]);
```

### Alteração 2: add_collect_chat_v4.php
**Localização**: Após linha 354 (configuração do cliente)

**Adicionar**:
```php
// Cliente para FlyingDonkeys
$clientFlyingDonkeys = new EspoApiClient('https://flyingdonkeys.com.br');
$clientFlyingDonkeys->setApiKey('d5bcb42f62d1d96f8090a1002b792335');
```

**Modificar chamada de API** (linha ~358):
```php
// Envio para TravelAngels
$responseTravelAngels = $client->request('POST', 'Lead', [
    'firstName'      => $name,
    'cCelular'       => $cel,
    'cCpftext'       => $cpf,
    'cPlaca'         => $placa,
    'addressPostalCode' => $cep,
    'cGclid'         => $gclid,
    'emailAddress'   => $email,
    'cMarca'         => $veiculo,
    'cAnoMod'        => $anoModelo,
]);

// Envio para FlyingDonkeys
$responseFlyingDonkeys = $clientFlyingDonkeys->request('POST', 'Lead', [
    'firstName'      => $name,
    'cCelular'       => $cel,
    'cCpftext'       => $cpf,
    'cPlaca'         => $placa,
    'addressPostalCode' => $cep,
    'cGclid'         => $gclid,
    'emailAddress'   => $email,
    'cMarca'         => $veiculo,
    'cAnoMod'        => $anoModelo,
]);
```

## Impacto
- **Médio risco**: Duplicação de chamadas de API
- **Funcionalidade**: Mantém envio para TravelAngels + adiciona FlyingDonkeys
- **Performance**: Duplica tempo de processamento
- **Benefício**: Dados sincronizados em ambos os sistemas

## Riscos e Mitigações
- **Risco**: Falha em um dos sistemas
- **Mitigação**: Try-catch separado para cada chamada
- **Risco**: Timeout duplo
- **Mitigação**: Timeout adequado configurado
- **Risco**: Dados inconsistentes
- **Mitigação**: Mesmo payload para ambos os sistemas

## Cronograma Estimado
- **Análise**: 10 minutos
- **Implementação**: 30 minutos (2 arquivos)
- **Testes**: 20 minutos
- **Deploy**: 10 minutos
- **Validação**: 15 minutos
- **Total**: ~85 minutos

## Critérios de Sucesso
1. Ambos os arquivos enviam dados para TravelAngels
2. Ambos os arquivos enviam dados para FlyingDonkeys
3. Mesmas API keys utilizadas em ambos os sistemas
4. Mesmo formato de dados enviado
5. Logs separados para cada sistema
6. Funcionalidade existente permanece intacta
7. Teste com dados reais funciona em ambos os sistemas

## Comparação de Sistemas

| Aspecto | Antes | Depois |
|---------|-------|--------|
| **Sistemas de destino** | TravelAngels | TravelAngels + FlyingDonkeys |
| **Chamadas de API** | 1 por arquivo | 2 por arquivo |
| **API Keys** | 1 por arquivo | 1 por arquivo (mesma key) |
| **Formato de dados** | Único | Único (duplicado) |
| **Cobertura** | 1 sistema | 2 sistemas |

## Configurações dos Sistemas

### TravelAngels
- **Endpoint**: `https://travelangels.com.br`
- **API Key (TravelAngels)**: `7a6c08d438ee131971f561fd836b5e15`
- **API Key (Collect.chat)**: `d5bcb42f62d1d96f8090a1002b792335`

### FlyingDonkeys
- **Endpoint**: `https://flyingdonkeys.com.br`
- **API Key (TravelAngels)**: `7a6c08d438ee131971f561fd836b5e15`
- **API Key (Collect.chat)**: `d5bcb42f62d1d96f8090a1002b792335`

## Status do Projeto
- **Criado em**: 13/10/2025
- **Status**: Aguardando implementação
- **Prioridade**: Média
- **Responsável**: Desenvolvedor

## Notas
- Este plano foi criado para duplicar dados entre TravelAngels e FlyingDonkeys
- As mesmas API keys serão utilizadas em ambos os sistemas
- O mesmo formato de dados será enviado para ambos os endpoints
- Logs separados permitirão monitoramento individual de cada sistema
- Após implementação, validar com dados reais em ambos os sistemas

---

**Arquivo**: `PLANO_PROJETO_FLYINGDONKEYS.md`  
**Versão**: 1.0  
**Última atualização**: 13/10/2025
