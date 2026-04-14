# Oracle Membership API

[![Tests](https://github.com/your-username/oracle-membership-api/actions/workflows/tests.yml/badge.svg)](https://github.com/your-username/oracle-membership-api/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)

Uma API RESTful construída com Laravel para gerenciamento de membros, planos e pagamentos. Inclui autenticação via Laravel Sanctum e documentação automática de API com Scribe.

## Funcionalidades

- **Autenticação**: Login/logout com tokens via Sanctum
- **Usuários**: Gerenciamento de usuários (admin e comuns)
- **Membros**: CRUD completo para membros
- **Planos**: Gerenciamento de planos de assinatura (apenas admin)
- **Pagamentos**: CRUD para pagamentos associados a membros
- **Dashboard**: Estatísticas e visão geral
- **Documentação de API**: Gerada automaticamente com Scribe

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

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
# membership-api
