# slim-api-template

This project was created from the **[Slim API Template](https://github.com/andrewdyer/slim-api-template)**.

## Getting Started

Before working on the codebase, read the **[contributing guide](./CONTRIBUTING.md)**.

### Prerequisites

Before starting, ensure the following are available:

- [PHP](https://www.php.net/) 8.3 or later.
- [Composer](https://getcomposer.org/) for dependency management.
- A database compatible with the application configuration.
- [Docker](https://www.docker.com/) (optional) when using the template's local development database container.

### Install

Install the dependencies:

```bash
composer install
```

### Configure

Create the local environment file:

```bash
cp .env.example .env
```

Replace the placeholder values and configure the database connection for the local environment. Keep populated environment files out of version control.

### Database

The application can connect to any compatible database configured in `.env`. Alternatively, start the included development MySQL container:

```bash
docker compose -f compose.dev.yaml up -d
```

The container uses the `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, and `MYSQL_ROOT_PASSWORD` values from `.env` and exposes MySQL on `127.0.0.1:3307`.

Once the configured database is available, run the migrations:

```bash
composer db:migrate
```

### Start

Start the development server:

```bash
php -S 127.0.0.1:8888 -t public public/index.php
```

The API is available at `http://127.0.0.1:8888`.

## Documentation

Learn about environment setup, testing, Docker, and the application architecture in the [template docs](https://docs.dyerlabs.co.uk/templates/slim-api-template/introduction).
