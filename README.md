![Slim API Template](https://public-assets.andrewdyer.rocks/images/covers/slim-api-template.png)

# Slim API Template

A template for building structured, maintainable, and scalable REST APIs in [Slim Framework](https://www.slimframework.com/) applications, following ADR architecture and clear separation of concerns.

## Introduction

This template provides a foundation for building structured, maintainable, and scalable REST APIs in Slim Framework applications, following Action–Domain–Responder (ADR) architecture and clear separation of concerns. It includes a shutdown handler for consistent error responses, a dedicated CORS response emitter, structured logging via Monolog, and environment configuration through PHP dotenv. Working alongside Slim’s error middleware, these components ensure uniform behaviour, consistent error payloads, and seamless integration of cross-cutting concerns. The setup also includes modern tooling, testing, and optional containerisation, along with a complete feature example for reference or extension.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- **[PHP](https://www.php.net/)**: Version 8.3 or higher is required.
- **[Composer](https://getcomposer.org/)**: Dependency management tool for PHP.
- **[Docker](https://www.docker.com/)** (optional): For running the application in a containerised environment.

## Structure

This project follows an ADR-style architecture, organising code by responsibility:

- **Application**: HTTP layer and use-case coordination
- **Domain**: Core business logic and contracts
- **Infrastructure**: External integrations and implementations

The structure is intentionally simple and feature-oriented, allowing features to grow vertically without blurring boundaries between layers.

```plaintext
repo/
├── app/                         # PSR-4 Autoloaded logic
│   ├── Application/             # Request handling & orchestration
│   ├── Domain/                  # Business logic & interface contracts
│   ├── Infrastructure/          # External integrations & implementations
│   │   └── Factory/             # Application bootstrapping factories
│   └── helpers.php              # Global utility functions
│
├── bootstrap/                   # Application configuration layer
│   ├── database.php             # Database initialisation
│   ├── dependencies.php         # Container service registrations
│   ├── middleware.php           # HTTP middleware pipeline configuration
│   ├── repositories.php         # Interface → implementation bindings
│   ├── routes.php               # Slim route definitions
│   └── settings.php             # Configuration arrays
│
├── public/                      # Web server document root
│   └── index.php                # HTTP entry point
│
├── resources/                   # Development & documentation assets
│   └── http/                    # HTTP request/response examples
│
├── storage/                     # File-based storage
│   └── logs/                    # Monolog output
│
├── tests/                       # PHPUnit Test Suite
│   ├── Integration/             # Full HTTP stack tests
│   └── Unit/                    # Isolated logic tests
│
├── workbench/                   # Scratchpad for local experimentation
│
├── composer.json               # Project dependencies and scripts
├── Dockerfile                  # Container build instructions
├── phpunit.xml                 # PHPUnit configuration
└── .env.example                # Example environment variables file
```

The template includes a simple **Users** feature to demonstrate how a vertical slice can be structured using the ADR approach. This example shows how a single feature can be organised across all layers:

- **Actions**: HTTP entry points for creating, retrieving, listing, updating, and deleting users.
- **DTOs**: Data transfer objects for input and output.
- **Service**: Application logic orchestrated in a dedicated service class.
- **Domain**: Entity and repository contracts define the business model and persistence interface.
- **Infrastructure**: In-memory repository implementation for demonstration purposes.

Routes are versioned under `/api/v1` and follow RESTful conventions:

| Method   | Path                 | Description     |
| -------- | -------------------- | --------------- |
| `GET`    | `/api/v1/users`      | List all users  |
| `POST`   | `/api/v1/users`      | Create a user   |
| `GET`    | `/api/v1/users/{id}` | Retrieve a user |
| `PUT`    | `/api/v1/users/{id}` | Update a user   |
| `DELETE` | `/api/v1/users/{id}` | Delete a user   |

This feature is provided as a reference and starting point. It may be used as a template for additional features, or removed entirely when initialising a new project.

## Bootstrapping

Bootstrapping is split across two distinct areas: a factory class that assembles the application, and a set of configuration files used to customise behaviour.

### Application Factory

The `ApplicationFactory` class acts as the composition root of the system, responsible for assembling and configuring a runnable application instance.

It wires together configuration, dependencies, middleware, and runtime behaviour into a single bootstrapped application.

For HTTP requests, `public/index.php` delegates to the factory. The factory then builds the Slim application and returns a runtime-ready application wrapper.

This design keeps bootstrapping explicit, testable, and separate from both framework entry points and domain logic.

### Configuration Files

Application configuration is organised inside the `bootstrap/` directory and is loaded during application creation. Each file defines a specific part of the application wiring without containing runtime or domain logic.

Configuration values and environment mappings are defined in `settings.php`, which provides the base configuration used throughout the application.

Service registrations for the dependency injection container are defined in `dependencies.php`, which controls how infrastructure services are constructed.

Interface to implementation bindings are defined in `repositories.php`, which maps domain contracts to concrete implementations.

Database initialisation is configured in `database.php`, which provides a hook for setting up database connections, booting ORMs (Eloquent, Doctrine), configuring raw PDO connections, or running migrations.

The HTTP middleware pipeline is configured in `middleware.php`, which defines how incoming requests are processed.

Slim route definitions are defined in `routes.php`, which maps HTTP endpoints to application actions.

## Dependencies

Key runtime packages are managed via Composer, including:

- **[Slim Framework](https://www.slimframework.com/)** (v4) for routing, middleware composition, and HTTP application flow.
- **[Slim PSR-7](https://github.com/slimphp/Slim-Psr7)** for handling HTTP requests and responses via a PSR-7 implementation.
- **[PHP-DI](https://php-di.org/)** for dependency injection and service container management.
- **[Monolog](https://seldaek.github.io/monolog/)** for structured application logging.
- **[CORS Response Emitter](https://github.com/andrewdyer/cors-response-emitter)** for CORS-aware response emission.
- **[JSON Error Handler](https://github.com/andrewdyer/json-error-handler)** for consistent JSON-formatted error responses.
- **[Shutdown Handler](https://github.com/andrewdyer/shutdown-handler)** for consistent shutdown and fatal error handling.
- **[phpdotenv](https://github.com/vlucas/phpdotenv)** for loading environment-based configuration.

See [composer.json](./composer.json) for the full list.

## Tooling

Development tooling is included for a consistent and reliable workflow:

- **[PHPUnit](https://phpunit.de/)** for unit and integration testing.
- **[PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)** for automated code style enforcement.
- **[Docker](https://www.docker.com/)** for containerised development and deployment environments.
- **[GitHub Actions](https://github.com/features/actions)** CI (Continuous Integration) workflow that runs on pushes to main and pull requests, executing unit and integration tests.

These tools ensure code quality, reproducibility, and smooth collaboration.

## Getting Started

If you like what you've seen so far and think this setup fits your needs, you can quickly get started by clicking the **Use this template** button at the top of the repository on GitHub.

## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT) and is free for private or commercial projects.
