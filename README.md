# Sistema de Biblioteca

Sistema web para gerenciamento de livros, autores e assuntos.

## Tecnologias utilizadas

- PHP 8.4
- Symfony 7.4
- Twig
- PostgreSQL 17
- Doctrine ORM
- Bootstrap 5.3
- PHPUnit
- Docker e Docker Compose

## Requisitos

- Docker Desktop instalado.
- Docker Compose disponível.

## Instalação e execução

Clone o repositório:

```bash
git clone https://github.com/pedroguedes7/biblioteca.git
cd biblioteca
```

Construa e inicie os containers:

```bash
docker compose up -d --build
```

Instale as dependências:

```bash
docker compose exec app composer install
```

Execute as migrations:

```bash
docker compose exec app php bin/console doctrine:migrations:migrate
```

Acesse a aplicação:

http://localhost:8080

## Funcionalidades

- Cadastro, edição, listagem e exclusão de livros.
- Cadastro, edição, listagem e exclusão de autores.
- Cadastro, edição, listagem e exclusão de assuntos.
- Relacionamento entre livros, autores e assuntos.
- Relatório de livros agrupados por autor, utilizando VIEW no PostgreSQL.

## Testes automatizados

Execute:

```bash
docker compose exec app php bin/phpunit
```

## Estrutura do projeto

- `src/Entity`: entidades e mapeamentos.
- `src/Controller`: controladores da aplicação.
- `src/Form`: formulários e validações.
- `src/Repository`: consultas e acesso aos dados.
- `templates`: templates Twig.
- `migrations`: scripts de migração do banco de dados.
- `tests`: testes automatizados.

## Encerramento

Para interromper os containers:

```bash
docker compose down
```

Para remover também os dados persistidos do PostgreSQL:

```bash
docker compose down -v
```

