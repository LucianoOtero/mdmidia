# 📋 PROJETO: INCLUSÃO DO CAMPO SOURCE NOS ENDPOINTS

## 🎯 **OBJETIVO**

Padronizar o campo `source` (Origem) em todos os endpoints para identificar corretamente a origem dos leads no EspoCRM.

## 📊 **SITUAÇÃO ATUAL**

| Endpoint | Campo Source | Status |
|----------|--------------|--------|
| `add_leadsgo.php` | ✅ `'Baeta'` | **CORRETO** |
| `add_travelangels.php` | ❌ **AUSENTE** | **PROBLEMA** |
| `add_collect_chat.php` | ❌ **AUSENTE** | **PROBLEMA** |

## 🔧 **MODIFICAÇÕES PLANEJADAS**

### **1. add_collect_chat.php**

- **Campo**: `source`
- **Valor**: `'Collect Chat'`
- **Localização**: Payload do EspoCRM (linha ~372)
- **Log**: Adicionar log do source antes do envio

### **2. add_travelangels.php**

- **Campo**: `source`
- **Valor**: `'Site'`
- **Localização**: Payload do EspoCRM (linha ~56)
- **Log**: Adicionar log do source antes do envio

## 📝 **ETAPAS DO PROJETO**

### **Fase 1: Backup com Data/Hora Atual**

1. ✅ Analisar estrutura atual dos arquivos
2. 🔄 Criar backup dos arquivos originais com data/hora atual
   - `add_collect_chat_v_20250113_[HORA].php`
   - `add_travelangels_v_20250113_[HORA].php`

### **Fase 2: Backup Versão 5**

3. 🔄 Criar backup dos arquivos originais como versão 5
   - `add_collect_chat_v5.php`
   - `add_travelangels_v5.php`

### **Fase 3: Implementação nas Versões 5**

4. 🔄 Modificar `add_collect_chat_v5.php`:

   ```php
   // Adicionar após linha ~55 (extração de campos)
   $source = 'Collect Chat';
   
   // Adicionar log
   fwrite($logs, "Source: " . $source . PHP_EOL);
   
   // Adicionar ao payload (linha ~372)
   'source' => $source,
   ```

5. 🔄 Modificar `add_travelangels_v5.php`:

   ```php
   // Adicionar após linha ~28 (extração de campos)
   $source = 'Site';
   
   // Adicionar log
   fwrite($logs, "Source: " . $source . PHP_EOL);
   
   // Adicionar ao payload (linha ~56)
   'source' => $source,
   ```

### **Fase 4: Teste das Versões 5**

6. 🔄 Criar arquivo de teste `test_source_v5.php`
7. 🔄 Testar ambos os endpoints _v5 modificados
8. 🔄 Verificar se retornam status "OK"
9. 🔄 Verificar logs para confirmar inclusão do source

### **Fase 5: Validação Manual**

10. 🔄 **SOLICITAR CONFIRMAÇÃO**: Verificar se os leads foram incluídos corretamente no EspoCRM
    - Confirmar se campo `source` aparece com valores corretos
    - Verificar se dados estão completos
    - Validar se não há duplicação

### **Fase 6: Documentação**

11. 🔄 Atualizar `CHANGELOG.md` com as modificações
12. 🔄 Documentar os novos valores de source

## 🎯 **RESULTADO ESPERADO**

### **Após Implementação:**

| Endpoint | Campo Source | Valor |
|----------|--------------|-------|
| `add_leadsgo.php` | ✅ `source` | `'Baeta'` |
| `add_travelangels.php` | ✅ `source` | `'Site'` |
| `add_collect_chat.php` | ✅ `source` | `'Collect Chat'` |

## 📋 **CÓDIGO ESPECÍFICO A SER ADICIONADO**

### **add_collect_chat.php**

```php
// Após linha ~55 (após extração de campos)
$source = 'Collect Chat';

// Após linha ~63 (após log dos dados extraídos)
fwrite($logs, "Source: " . $source . PHP_EOL);

// No payload (linha ~372), adicionar:
'source' => $source,
```

### **add_travelangels.php**

```php
// Após linha ~28 (após extração de campos)
$source = 'Site';

// Após linha ~42 (após log do nome)
fwrite($logs, "Source: " . $source . PHP_EOL);

// No payload (linha ~56), adicionar:
'source' => $source,
```

## ⚠️ **CONSIDERAÇÕES IMPORTANTES**

1. **Compatibilidade**: As modificações não afetam a funcionalidade existente
2. **Logs**: Adicionar logs para facilitar debugging
3. **Consistência**: Manter padrão similar ao `add_leadsgo.php`
4. **Testes**: Validar em ambos os sistemas (TravelAngels e FlyingDonkeys)

## 🚀 **BENEFÍCIOS**

1. **Rastreabilidade**: Identificar origem de todos os leads
2. **Relatórios**: Melhor análise de performance por canal
3. **Padronização**: Consistência entre todos os endpoints
4. **Manutenção**: Facilita identificação de problemas por origem

---

**Status**: 📋 **PROJETO CRIADO - AGUARDANDO EXECUÇÃO**
**Prioridade**: 🔥 **ALTA** (Correção de inconsistência crítica)
**Estimativa**: ⏱️ **30 minutos** (Implementação + Testes)
