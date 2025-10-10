# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [2.0.0] - 2025-10-09

### Adicionado
- Sistema de autoload PSR-4 para melhor organização do código
- Suporte a PHPUnit para testes automatizados
- Scripts de validação e teste no composer.json
- Configuração de otimização de autoloader
- Documentação completa do projeto
- Sistema de logging aprimorado com timestamps
- Validação de métodos HTTP (GET/POST)
- Tratamento de erros específicos por cenário
- Suporte a múltiplas fontes de leads simultaneamente
- Validação de dados de entrada aprimorada
- Sistema de deduplicação de leads no EspoCRM
- Endpoints para diferentes plataformas:
  - LeadsGo (leadsgo.online)
  - TravelAngels (webflow segurosimediato.com.br)
  - Collect.chat
  - Octadesk
  - Webflow + Octadesk

### Alterado
- Melhorado tratamento de exceções no cliente EspoCRM
- Otimizado sistema de logs para melhor debugging
- Aprimorada validação de CPF e placa
- Melhorada estrutura de resposta dos endpoints
- Atualizado sistema de mapeamento de dados

### Corrigido
- Corrigido bug no `class.php` que causava exceções em respostas válidas do EspoCRM
- Removido flag `forceDuplicate` para evitar duplicação de leads
- Corrigido mapeamento de dados do LeadsGo
- Corrigido tratamento de métodos HTTP incorretos
- Corrigido parsing de dados JSON vazios

### Removido
- Flag `forceDuplicate` de todos os endpoints
- Dependências desnecessárias
- Código obsoleto de tratamento de DDD

### Segurança
- Validação rigorosa de dados de entrada
- Sanitização de dados antes do envio ao EspoCRM
- Validação de tipos de dados
- Proteção contra injeção de dados maliciosos

## [1.0.0] - 2025-10-09

### Adicionado
- Versão inicial do sistema de integração de leads
- Endpoint básico para LeadsGo
- Cliente EspoCRM API
- Sistema de logs básico
- Validação de CPF e placa
- Integração com múltiplas fontes de leads

---

## Tipos de Mudanças

- **Adicionado** para novas funcionalidades
- **Alterado** para mudanças em funcionalidades existentes
- **Depreciado** para funcionalidades que serão removidas em versões futuras
- **Removido** para funcionalidades removidas nesta versão
- **Corrigido** para correções de bugs
- **Segurança** para vulnerabilidades corrigidas
