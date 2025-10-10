# MDMidia - Sistema de Integração de Leads

Sistema de integração para recebimento de leads de múltiplas fontes e inserção no EspoCRM.

## 📋 Descrição

Este projeto contém endpoints PHP para receber webhooks de diferentes plataformas de captação de leads e integrá-los com o EspoCRM.

## 🚀 Funcionalidades

- **Integração com LeadsGo**: Endpoint para receber leads do leadsgo.online
- **Integração com TravelAngels**: Endpoint para receber leads do webflow segurosimediato.com.br
- **Integração com Collect.chat**: Endpoint para receber leads do chat
- **Integração com Octadesk**: Endpoint para integração com sistema de atendimento
- **Validação de dados**: Validação de CPF, placa e outros campos
- **Logs detalhados**: Sistema de logging com timestamps
- **Tratamento de erros**: Validação de métodos HTTP e dados
- **Autoload PSR-4**: Estrutura organizada para melhor manutenção
- **Testes automatizados**: Suporte a PHPUnit para testes unitários
- **Deduplicação inteligente**: Prevenção automática de leads duplicados
- **Segurança aprimorada**: Validação rigorosa e sanitização de dados
- **Múltiplos métodos HTTP**: Suporte a GET e POST
- **Respostas padronizadas**: JSON estruturado para todas as respostas

## 📁 Estrutura do Projeto

```
mdmidia/
├── add_leadsgo.php          # Endpoint para LeadsGo
├── add_travelangels.php     # Endpoint para TravelAngels
├── add_collect_chat.php     # Endpoint para Collect.chat
├── add_collect_octa.php     # Endpoint para Octadesk
├── add_webflow_octa.php     # Endpoint para Webflow + Octadesk
├── add.php                  # Endpoint genérico
├── class.php                # Cliente EspoCRM API
├── cpf-validate.php         # Validação de CPF
├── placa-validate.php       # Validação de placa
├── oportunidade.php         # Gerenciamento de oportunidades
├── planilha.php             # Exportação para planilha
└── composer.json            # Dependências PHP
```

## 🔧 Instalação

1. Clone o repositório:
```bash
git clone https://github.com/LucianoOtero/mdmidia.git
cd mdmidia
```

2. Instale as dependências:
```bash
composer install
```

3. Configure os endpoints no servidor web

## 📊 Endpoints Disponíveis

### LeadsGo
- **URL**: `https://mdmidia.com.br/add_leadsgo.php`
- **Método**: POST
- **Content-Type**: application/json
- **Descrição**: Recebe leads do leadsgo.online

### TravelAngels
- **URL**: `https://mdmidia.com.br/add_travelangels.php`
- **Método**: POST
- **Content-Type**: application/json
- **Descrição**: Recebe leads do webflow segurosimediato.com.br

### Collect.chat
- **URL**: `https://mdmidia.com.br/add_collect_chat.php`
- **Método**: POST
- **Content-Type**: application/json
- **Descrição**: Recebe leads do chat

## 🔐 Configuração

### EspoCRM
Configure as credenciais do EspoCRM no arquivo `class.php`:

```php
$client = new EspoApiClient('https://seu-espocrm.com');
$client->setApiKey('sua-api-key');
```

### Logs
Os logs são salvos em arquivos `.txt` no diretório do projeto:
- `logs_leadsgo.txt` - Logs do LeadsGo
- `logs_travelangels.txt` - Logs do TravelAngels
- `collect_chat_logs.txt` - Logs do Collect.chat

## 📝 Formato de Dados

### LeadsGo
```json
{
  "nome_segurado": "Nome do Cliente",
  "email": "cliente@email.com",
  "telefone": "11999999999",
  "cpf_segurado": "12345678901",
  "marca": "Toyota",
  "modelo": "Corolla",
  "placa": "ABC1234",
  "ano": "2020"
}
```

## 🚨 Tratamento de Erros

- **400 Bad Request**: Dados inválidos ou vazios
- **405 Method Not Allowed**: Método HTTP incorreto
- **500 Internal Server Error**: Erro interno do servidor

## 📈 Monitoramento

- Logs detalhados com timestamps
- Validação de dados de entrada
- Tratamento de exceções
- Respostas padronizadas

## 🔄 Versionamento

- **v2.0.0**: Sistema completo com autoload PSR-4, testes automatizados e melhorias de segurança
- **v1.0.0**: Versão inicial com integrações básicas

## 👥 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 📞 Suporte

Para suporte técnico, entre em contato através dos logs do sistema ou abra uma issue no GitHub.

---

**Desenvolvido por**: Luciano Otero  
**Versão**: 2.0.0  
**Última atualização**: 09/10/2025
