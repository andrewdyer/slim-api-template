# Contributing

Thank you for your interest in contributing! We welcome improvements and suggestions to make this project even better. Please follow the guidelines below for a smooth experience.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Development Setup](#development-setup)
- [Environment](#environment)
- [Configuration](#configuration)
- [Upgrading Dependencies](#upgrading-dependencies)
- [Testing](#testing)
- [Docker](#docker)
- [Coding Standards](#coding-standards)
- [Issue Reporting](#issue-reporting)
- [Commit Guidelines](#commit-guidelines)

## Code of Conduct

Please adhere to our [Code of Conduct](./CODE_OF_CONDUCT.md) in all interactions. Respectful and inclusive behavior is expected from all contributors.

## Development Setup

To get started with contributing, set up the project by following these steps:

1. Begin by cloning the repository and navigating to its directory.
2. Ensure you have PHP 8.3 or higher installed.
3. Copy the example environment file with `cp .env.example .env` and update the values for your local setup.
4. Install all project dependencies with `composer install`.
5. Start the built-in PHP development server with `php -S 127.0.0.1:8888 -t public public/index.php`.

## Environment

Environment configuration is managed via a `.env` file loaded at runtime using [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv). A `.env.example` file is committed to the repository as the canonical reference for all required variables — copy it to `.env` for local development.

The `.env` file should **never** be committed to version control. In production, set environment variables directly in the hosting environment; when `APP_ENV` is already set, the application skips loading the `.env` file automatically.

> ⚠️ **Important:** When adding a new environment variable, always add a corresponding entry to `.env.example` with a sensible placeholder or default value.

## Configuration

Application configuration and bootstrapping is organised inside the `bootstrap/` directory. These files define the application configuration, dependency bindings, middleware pipeline, route definitions, and HTTP application setup.

The `app.php` file is responsible for creating and configuring the Slim application instance used by HTTP entry points such as `public/index.php` and the integration test suite. It loads environment variables, builds the dependency injection container, registers middleware and routes, configures error handling, and returns a fully configured application ready to handle requests.

### Settings

Application configuration values and environment mappings are defined in `settings.php`, which provides the base configuration used throughout the application.

This file is typically used for environment-specific configuration values, feature toggles, and structured configuration arrays consumed across the system.

### Dependencies

Service registrations for the dependency injection container are defined in `dependencies.php`, which controls how infrastructure services are constructed and resolved at runtime.

This includes factory closures, external library wiring, and shared service definitions.

### Repositories

Interface to implementation bindings are defined in `repositories.php`, which maps domain interfaces to infrastructure implementations.

This ensures the Domain layer remains independent of persistence or external systems.

### Middleware

Custom middleware is registered in `middleware.php`, which applies global and feature-level middleware to the Slim application instance.

Middleware is executed in LIFO order and is responsible for cross-cutting concerns such as authentication and request transformation.

Error handling middleware is registered in `public/index.php` and does not need to be configured here.

### Routes

Slim route definitions are registered in `routes.php`, which attaches HTTP endpoints to their corresponding application actions.

Routes follow RESTful conventions and are typically grouped by feature or domain area. The standard set of routes for a resource is:

| Method   | Path             | Description         |
| -------- | ---------------- | ------------------- |
| `GET`    | `/resource`      | List all records    |
| `POST`   | `/resource`      | Create a new record |
| `GET`    | `/resource/{id}` | Retrieve a record   |
| `PUT`    | `/resource/{id}` | Update a record     |
| `DELETE` | `/resource/{id}` | Delete a record     |

## Upgrading Dependencies

Keeping dependencies up-to-date is crucial for maintaining the security and performance of the project.

1. Check for outdated packages with `composer outdated`.
2. Update all dependencies to their latest allowed versions with `composer update`.
3. Update a specific package with `composer update <vendor/package>`.
4. Run the test suite with `composer test` to verify nothing is broken after updating.
5. Commit both `composer.json` and `composer.lock` together with a clear message and open a pull request for review.

## Testing

Please write tests for any new features or modifications to the project.

Tests live under `tests/` and are organised into two suites:

- `tests/Unit/` — isolated unit tests for individual classes and methods
- `tests/Integration/` — tests that exercise multiple layers working together

The test suite is configured via `phpunit.xml`, which defines test suites, source directories, and environment variables. By default, the `APP_ENV` variable is set to `testing` when running tests, and this can be extended to suit your needs.

You can run tests using the provided Composer scripts:

- Run the full test suite with `composer test`
- Use `composer test:unit` for fast, isolated unit tests during development
- Use `composer test:integration` to verify end-to-end behaviour across layers

For consistency and maintainability:

- Keep tests focused and readable
- Prefer small, isolated test cases
- Use integration tests for HTTP actions where appropriate
- Avoid unnecessary complexity in setup

> 💡 **Note:** The testing setup is intentionally minimal and may evolve over time. Contributions to improve testing structure are welcome.

## Docker

This repository includes a Dockerfile based on the official `php:8.3-apache` image, with Apache configured to serve from the `public/` directory and `mod_rewrite` enabled for Slim's routing.

Build the image with `docker build -t slim-app:local .`.

Run the container with `docker run --name slim-app -p 8080:80 -d slim-app:local`.

The application will be available at `http://localhost:8080`.

> 💡 **Note:** When running via Docker, `APP_ENV` is typically set to `production`, so the application will not attempt to load a `.env` file. Pass any required environment variables at runtime using `-e` flags or a `--env-file`.

## Coding Standards

This project uses [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) to enforce a consistent code style. Run the formatter before committing your changes with `composer cs`.

- Follow modern PHP practices (typed properties, readonly where appropriate, strict types)
- Keep code simple and consistent with the existing structure

## Issue Reporting

We welcome bug reports, feature requests, and questions about the project. To ensure we can help you effectively, please use the appropriate issue template when creating a new issue, including:

- **🐛 Bug Report**: Report issues or unexpected behavior
- **✨ Feature Request**: Suggest new features or improvements
- **❓ Question**: Ask for help or clarification

Before creating an issue, please:

- Search existing issues to avoid duplicates
- Check the documentation and README for answers to common questions
- Use GitHub Discussions for general questions and community support

> 💡 **Tip:** When you create a new issue, GitHub will automatically show you the available templates. Choose the one that best fits your situation for a guided experience.

## Commit Guidelines

When contributing changes, it's important to follow clear commit practices that help maintain project history and make collaboration easier. Use descriptive commit messages following the [Conventional Commits](https://www.conventionalcommits.org/) format, and feel free to add emojis to quickly convey the type of change using [Git Commit Emoji](https://dev.andrewdyer.rocks/git-commit-emoji) conventions.

Once you've made your changes, follow these steps to submit them for review:

1. Create a feature branch with `git checkout -b feature/your-feature-name`.
2. Commit your changes following the commit guidelines.
3. Push your branch with `git push origin feature/your-feature-name`.
4. Open a pull request with a title and description that clearly explain your changes.
