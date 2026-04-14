# Oracle Membership API

[![Tests](https://github.com/your-username/oracle-membership-api/actions/workflows/tests.yml/badge.svg)](https://github.com/your-username/oracle-membership-api/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)

Uma API RESTful construída com Laravel para gerenciamento de membros, planos e pagamentos. Inclui autenticação via Laravel Sanctum e documentação automática de API com Scribe.

## Resumo de Valor

A Oracle Membership API oferece uma base sólida e estruturada para gerenciamento de membros e assinaturas, priorizando simplicidade, integridade de dados e facilidade de manutenção. Principais benefícios atuais:

- **Gestão Essencial de Membros:** CRUD básico com status automático baseado em pagamentos, assegurando controle financeiro fundamental.
- **Administração de Planos:** Criação e gerenciamento de planos com validações básicas para consistência.
- **Segurança Básica:** Autenticação via tokens e roles simples, adequada para cenários de baixo risco.
- **Documentação Automática:** Facilita integração inicial e desenvolvimento colaborativo.
- **Estrutura Modular:** Services e Policies permitem expansões futuras sem refatoração massiva.

Ideal como ponto de partida para sistemas de membership em startups ou projetos pequenos, com foco em core funcional e preparação para evoluções incrementais conforme necessidades crescem.

## Funcionalidades

- **Autenticação**: Login/logout com tokens via Sanctum
- **Usuários**: Gerenciamento de usuários (admin e comuns)
- **Membros**: CRUD completo para membros
- **Planos**: Gerenciamento de planos de assinatura (apenas admin)
- **Pagamentos**: CRUD para pagamentos associados a membros
- **Dashboard**: Estatísticas e visão geral
- **Documentação de API**: Gerada automaticamente com Scribe

## Regras de Negócio

Esta seção detalha as regras de negócio implementadas na API, explicando o raciocínio por trás de cada uma e os trade-offs considerados.

### Usuários
- **Apenas usuários com role `admin` podem registrar, editar e deletar outros usuários.**
  - **Por que?** Para controlar o acesso administrativo e prevenir que colaboradores modifiquem usuários sem permissão.
  - **Implementação:** Usado Policy `UserPolicy` (não mostrado, mas similar aos outros) para autorização.
- **Roles válidas: `admin` (acesso total) e `collaborator` (acesso limitado).**
  - **Por que?** Simplifica o controle de permissões sem complexidade excessiva.
  - **Trade-off:** Roles fixas limitam flexibilidade; para mais granularidade, poderia usar permissions (ex: Spatie Laravel Permission), mas adicionaria complexidade desnecessária para este projeto.
- **Validações: email único, senha com mínimo 8 caracteres.**
  - **Por que?** Segurança básica e integridade de dados.

### Membros
- **Criação: O plano selecionado deve estar ativo. Um pagamento inicial é gerado automaticamente para o mês atual.**
  - **Por que?** Garante que novos membros tenham planos válidos e inicia o ciclo de pagamentos imediatamente.
  - **Trade-off:** Pagamento automático simplifica onboarding, mas assume que o membro paga no ato da criação; em cenários reais, poderia haver período de trial.
- **Atualização: Se o plano for alterado, o novo plano deve estar ativo.**
  - **Por que?** Previne associações com planos descontinuados.
- **Exclusão: Não é possível deletar membros com pagamentos pendentes.**
  - **Por que?** Protege integridade financeira; pagamentos pendentes indicam obrigações não resolvidas.
  - **Trade-off:** Restritivo, mas necessário; alternativa seria marcar como "inativo" em vez de deletar.
- **Status: `active` se há pagamento para o mês atual ou data anterior ao vencimento; `overdue` caso contrário.**
  - **Por que?** Automatiza o monitoramento de inadimplência baseado em regras claras.
  - **Trade-off:** Lógica simples baseada em data; não considera prorrogações ou negociações.

### Pagamentos
- **Apenas usuários `admin` podem criar, editar ou deletar pagamentos.**
  - **Por que?** Pagamentos envolvem finanças; controle rigoroso necessário.
- **Pagamentos devem ser feitos sequencialmente para o próximo mês disponível.**
  - **Por que?** Previne gaps ou pagamentos fora de ordem, mantendo consistência mensal.
  - **Trade-off:** Restritivo; em sistemas flexíveis, permitiria pagamentos adiantados, mas complicaria a lógica.
- **Não é permitido duplicar pagamentos para o mesmo mês.**
  - **Por que?** Evita overpagamento e duplicatas.
- **Ao registrar/deletar um pagamento, o status do membro é atualizado.**
  - **Por que?** Mantém o status em sincronia com os pagamentos reais.

### Planos
- **Apenas usuários `admin` podem gerenciar planos.**
  - **Por que?** Planos afetam toda a base de membros; mudanças devem ser controladas.
- **Desativação/Exclusão: Não permitido se houver membros associados/ativos.**
  - **Por que?** Previne orfandade de dados e inconsistências.
  - **Trade-off:** Conservador; alternativa seria migrar membros para outro plano, mas adicionaria complexidade.

## Arquitetura e Decisões Técnicas

### Por que separar em Policies?
- **Separação de responsabilidades:** As Policies lidam exclusivamente com autorização (quem pode fazer o quê), mantendo os Controllers focados na lógica de requisição/resposta.
- **Reutilização:** Policies podem ser usadas em múltiplos lugares (Controllers, Gates, etc.).
- **Laravel Best Practices:** Segue o padrão do framework, facilitando manutenção e testes.
- **Trade-off:** Adiciona arquivos extras, mas melhora a organização; para projetos pequenos, poderia ser inline nos Controllers, mas reduziria legibilidade.

### Por que usar Services?
- **Encapsulamento da lógica de negócio:** Services concentram regras complexas (ex: validações de pagamentos, cálculos de status), tornando Controllers "thin" e fáceis de testar.
- **Reutilização:** Lógica pode ser chamada de múltiplos Controllers ou Jobs.
- **Testabilidade:** Services são fáceis de mockar e testar isoladamente.
- **Trade-off:** Requer injeção de dependência, adicionando boilerplate; para lógica simples, poderia estar nos Models, mas misturaria concerns.

### Estrutura Geral
- **Controllers:** Lidam com HTTP (validações básicas, respostas JSON).
- **Services:** Contêm lógica de negócio (criação, atualização, validações avançadas).
- **Policies:** Autorização baseada em usuário e modelo.
- **Models:** Representação de dados e relacionamentos.
- **Por que essa estrutura?** Segue princípios SOLID, facilita testes unitários e manutenção. Trade-off: Mais arquivos, mas melhor escalabilidade para projetos maiores.

## Destaques Técnicos

- **Framework Moderno:** Laravel 12.x com PHP 8.2+, aproveitando features como enums, readonly properties e melhor performance.
- **Autenticação Segura:** Laravel Sanctum para tokens API, com suporte a refresh e revogação.
- **ORM Eficiente:** Eloquent com relacionamentos otimizados e casts automáticos (ex: decimal para preços).
- **Validações Robustas:** Regras de negócio aplicadas em Services, com exceções customizadas para feedback claro.
- **Documentação Viva:** Scribe gera docs interativas, Postman collections e OpenAPI specs automaticamente.
- **Testes Automatizados:** PHPUnit integrado com CI/CD via GitHub Actions, garantindo qualidade contínua.
- **Banco de Dados Flexível:** Suporte a MySQL/PostgreSQL/SQLite, com migrações versionadas e seeders.
- **Separação de Concerns:** Policies para auth, Services para lógica, Controllers thin – facilita manutenção e testes.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM (para assets frontend, se aplicável)
- Banco de dados (MySQL, PostgreSQL, SQLite, etc.)

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/your-username/oracle-membership-api.git
   cd oracle-membership-api
   ```

2. Instale as dependências do PHP:
   ```bash
   composer install
   ```

3. Instale as dependências do Node.js (se houver assets):
   ```bash
   npm install
   ```

4. Copie o arquivo de configuração do ambiente:
   ```bash
   cp .env.example .env
   ```

5. Configure o arquivo `.env` com suas credenciais de banco de dados e outras configurações necessárias.

6. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

7. Execute as migrações do banco de dados:
   ```bash
   php artisan migrate
   ```

8. (Opcional) Execute os seeders para dados de exemplo:
   ```bash
   php artisan db:seed
   ```

## Executando a Aplicação

Para iniciar o servidor de desenvolvimento:
```bash
php artisan serve
```

A API estará disponível em `http://localhost:8000`.

## Executando os Testes

Para executar os testes unitários e de funcionalidade:
```bash
php artisan test
```

## Documentação da API

A documentação completa da API é gerada automaticamente usando o pacote Scribe.

Para gerar a documentação:
```bash
php artisan scribe:generate
```

Após iniciar o servidor (`php artisan serve`), a documentação estará disponível em `http://localhost:8000/docs`.

Também são gerados arquivos Postman collection e OpenAPI specification em `storage/app/private/scribe/`.

## Estrutura do Projeto

- `app/Models/`: Modelos Eloquent (User, Member, Payment, Plan)
- `app/Http/Controllers/Api/`: Controladores da API
- `app/Services/`: Lógica de negócio
- `app/Policies/`: Políticas de autorização
- `database/migrations/`: Migrações do banco de dados
- `routes/api.php`: Definições das rotas da API
- `tests/`: Testes automatizados

## Limitações Conhecidas

Esta seção resume as principais limitações do projeto atual:

- **Ambientes distribuídos:** Não preparado para múltiplos servidores; falta cache distribuído e filas de mensagens.
- **Concorrência:** Sem controle de race conditions em operações críticas como pagamentos.
- **Gateway de pagamento:** Sem integração; pagamentos manuais apenas.
- **Tempo real:** Atualizações de status não são em tempo real; sem WebSockets.
- **Notificações:** Ausência de emails/SMS para alertas e lembretes.
- **Controle de acesso:** Roles simples; sem permissões granulares.
- **Auditoria:** Sem logs de mudanças para compliance.
- **Rate limiting:** Não implementado, vulnerável a abusos.
- **Testes:** Cobertura insuficiente; faltam testes de integração e carga.
- **Internacionalização:** Apenas português; sem suporte a múltiplos idiomas.
- **Escalabilidade:** Limitada a um banco único; sem sharding.
- **Segurança:** Básica; falta 2FA, bloqueio de conta, etc.

Essas limitações mantêm o projeto simples, focado no core funcional.

## Possíveis Evoluções

Sugestões resumidas para melhorias futuras:

- **Pagamentos Automáticos:** Integração com Stripe/PayPal para processamento e webhooks.
- **Concorrência e Distribuição:** Locks de DB, Redis para cache/filas, e Laravel Horizon para jobs.
- **Tempo Real e Notificações:** Broadcasting com Pusher e Mail/SMS (Twilio) para alertas.
- **Acesso e Segurança Avançados:** Permissões granulares (Spatie), 2FA (Fortify), auditoria (Auditing).
- **Performance e Escalabilidade:** Rate limiting, sharding, cache (Redis), monitoramento (Telescope).
- **Qualidade e Internacionalização:** Testes expandidos (Dusk, Artillery), i18n, APIs versionadas.

Priorize com base em necessidades do projeto para evolução incremental.

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
# membership-api
