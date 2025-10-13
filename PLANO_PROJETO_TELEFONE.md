# Plano de Projeto: Correção do Campo Telefone

## Objetivo
1. Alterar o mapeamento do campo `telefone` para `telefone_celular` no `add_leadsgo.php` para corresponder à documentação da API do LeadsGo.
2. Incluir novos campos do LeadsGo no envio para o EspoCRM:
   - `cSegpref` ← `seguradora_preferencia`
   - `cValorpret` ← `valor_preferencia`
   - `cModalidade` ← `modalidade_seguro`
   - `cSegant` ← `seguradora_apolice`
   - `cCiapol` ← `ci`

## Análise Atual
- **Campo atual no código**: `telefone` (linha 91)
- **Campo na API LeadsGo**: `telefone_celular`
- **Campo no EspoCRM**: `cCelular` (linha 127)
- **Problema**: Incompatibilidade entre código e documentação da API
- **Oportunidade**: Incluir 5 novos campos importantes do LeadsGo no EspoCRM

## Plano de Execução

### 1. Análise do mapeamento atual ✅
- Verificar linha 91: `$telefone = isset($data['telefone']) ? $data['telefone'] : '';`
- Confirmar que está sendo enviado para `cCelular` (linha 127)
- Identificar necessidade de alteração

### 2. Implementação da correção
- Alterar linha 91: `$telefone = isset($data['telefone_celular']) ? $data['telefone_celular'] : '';`
- Manter envio para `cCelular` no EspoCRM
- **NOVO**: Adicionar mapeamento dos 5 novos campos
- **NOVO**: Incluir novos campos no payload do EspoCRM
- Preservar funcionalidade existente

### 3. Atualização de logs
- Verificar se logs precisam ser atualizados
- Manter logs existentes para debugging

### 4. Testes
- Teste local com dados simulados
- Validação do novo mapeamento
- Verificação de logs

### 5. Deploy
- Upload do arquivo corrigido
- Teste em ambiente de produção
- Validação no EspoCRM

### 6. Validação final
- Confirmar que `telefone_celular` está sendo recebido
- Verificar se o valor está sendo salvo em `cCelular`
- Testar com lead real do LeadsGo

## Alterações Técnicas Necessárias

### Alteração 1: Mapeamento do campo telefone (Linha 91)
**Alteração atual**:
```php
$telefone = isset($data['telefone']) ? $data['telefone'] : '';
```

**Alteração proposta**:
```php
$telefone = isset($data['telefone_celular']) ? $data['telefone_celular'] : '';
```

### Alteração 2: Adicionar novos campos (Linhas ~108-112)
**Adicionar após linha 109**:
```php
$seguradoraPref = isset($data['seguradora_preferencia']) ? $data['seguradora_preferencia'] : '';
$valorPref = isset($data['valor_preferencia']) ? $data['valor_preferencia'] : '';
$modalidade = isset($data['modalidade_seguro']) ? $data['modalidade_seguro'] : '';
$seguradoraAnt = isset($data['seguradora_apolice']) ? $data['seguradora_apolice'] : '';
$ciApol = isset($data['ci']) ? $data['ci'] : '';
```

### Alteração 3: Incluir novos campos no payload (Linha ~138)
**Adicionar no array do payload**:
```php
$response = $client->request('POST', 'Lead', [
    // ... campos existentes ...
    'cSegpref' => $seguradoraPref,
    'cValorpret' => $valorPref,
    'cModalidade' => $modalidade,
    'cSegant' => $seguradoraAnt,
    'cCiapol' => $ciApol,
]);
```

## Impacto
- **Baixo risco**: Mudança no nome do campo + adição de 5 novos campos
- **Funcionalidade**: Mantém envio para `cCelular` + adiciona 5 novos campos
- **Compatibilidade**: Alinha com documentação da API
- **Benefício**: Maior riqueza de dados no EspoCRM

## Riscos e Mitigações
- **Risco**: Campo `telefone_celular` não existir no JSON
- **Mitigação**: Usar `isset()` para verificar existência
- **Risco**: Novos campos não existirem no JSON
- **Mitigação**: Usar `isset()` para todos os novos campos
- **Risco**: Quebra da funcionalidade existente
- **Mitigação**: Manter estrutura de fallback
- **Risco**: Campos do EspoCRM não existirem
- **Mitigação**: Verificar estrutura do EspoCRM antes do deploy

## Cronograma Estimado
- **Análise**: 5 minutos
- **Implementação**: 15 minutos (5 campos novos)
- **Testes**: 15 minutos
- **Deploy**: 5 minutos
- **Validação**: 15 minutos
- **Total**: ~55 minutos

## Critérios de Sucesso
1. Campo `telefone_celular` é mapeado corretamente
2. Valor é enviado para `cCelular` no EspoCRM
3. **NOVO**: 5 novos campos são mapeados corretamente
4. **NOVO**: Novos campos são enviados para o EspoCRM
5. Funcionalidade existente permanece intacta
6. Logs refletem o novo mapeamento
7. Teste com lead real funciona
8. **NOVO**: Todos os campos aparecem no EspoCRM com valores corretos

## Comparação de Campos

| Aspecto | Antes | Depois |
|---------|-------|--------|
| **Campo API** | `telefone` | `telefone_celular` |
| **Campo EspoCRM** | `cCelular` | `cCelular` |
| **Novos campos** | ❌ 0 campos | ✅ 5 campos |
| **Funcionalidade** | ✅ Funciona | ✅ Funciona |
| **Documentação** | ❌ Incompatível | ✅ Compatível |
| **Riqueza de dados** | Básica | Completa |

### Novos Campos Adicionados

| Campo EspoCRM | Campo LeadsGo | Descrição |
|---------------|---------------|-----------|
| `cSegpref` | `seguradora_preferencia` | Seguradora preferida |
| `cValorpret` | `valor_preferencia` | Valor preferencial |
| `cModalidade` | `modalidade_seguro` | Modalidade do seguro |
| `cSegant` | `seguradora_apolice` | Seguradora anterior |
| `cCiapol` | `ci` | CI da apólice |

## Status do Projeto
- **Criado em**: 10/10/2025
- **Status**: Aguardando implementação
- **Prioridade**: Média
- **Responsável**: Desenvolvedor

## Notas
- Este plano foi criado para corrigir incompatibilidade entre código e documentação da API
- A alteração é de baixo risco e mantém funcionalidade existente
- **NOVO**: Adiciona 5 campos importantes do LeadsGo ao EspoCRM
- **NOVO**: Melhora significativamente a riqueza de dados dos leads
- Após implementação, validar com lead real do LeadsGo
- **NOVO**: Verificar se todos os campos do EspoCRM existem antes do deploy

---

**Arquivo**: `PLANO_PROJETO_TELEFONE.md`  
**Versão**: 1.0  
**Última atualização**: 10/10/2025
