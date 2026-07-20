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

This project follows an ADR-style architecture, organising code by layer and responsibility:

- **Application**: HTTP layer and use-case coordination
- **Domain**: Core business logic and contracts
- **Infrastructure**: External integrations and implementations

The structure is intentionally simple and responsibility-oriented. Shared application concerns are grouped by their role, while domain contracts remain independent of infrastructure implementations.

```plaintext
repo/
├── app/                         # PSR-4 autoloaded application code
│   ├── Application/             # Request handling and use-case orchestration
│   │   ├── Actions/             # HTTP actions
│   │   ├── DTOs/                # Application input and output objects
│   │   │   ├── Input/           # Validated use-case input
│   │   │   └── Output/          # Serializable response output
│   │   ├── Exceptions/          # Application-specific exceptions
│   │   └── Services/            # Application services
│   ├── Domain/                  # Business models and contracts
│   │   ├── Models/              # Domain models
│   │   └── Repositories/        # Repository interfaces
│   ├── Infrastructure/          # External integrations and implementations
│   │   └── Persistence/
│   │       ├── Models/          # Eloquent persistence models
│   │       └── Repositories/    # Repository implementations
│   └── helpers.php              # Global utility functions
│
├── bootstrap/                   # Application configuration layer
│   ├── app.php                  # Application bootstrap and initialization
│   ├── database.php             # Database integration setup
│   ├── dependencies.php         # Container service registrations
│   ├── environment.php          # Environment variable loading
│   ├── middleware.php           # HTTP middleware pipeline configuration
│   ├── repositories.php         # Interface → implementation bindings
│   ├── routes.php               # Slim route definitions
│   └── settings.php             # Configuration arrays
│
├── database/                    # Database migrations and seeders
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
│   ├── Support/                 # Test factories and in-memory implementations
│   └── Unit/                    # Isolated logic tests
│
├── workbench/                   # Scratchpad for local experimentation
│
├── composer.json               # Project dependencies and scripts
├── Dockerfile                  # Container build instructions
├── phpunit.xml                 # PHPUnit configuration
└── .env.example                # Example environment variables file
```

The template includes a simple **Users** API to demonstrate how a feature flows through the ADR layers while the code remains grouped by responsibility:

- **Actions**: HTTP entry points for creating, retrieving, listing, updating, and deleting users.
- **Input/Output**: Application data objects for use-case input and serializable output.
- **Service**: Application logic orchestrated in a dedicated service class.
- **Domain**: The `User` model and `UserRepositoryInterface` define the business model and persistence contract.
- **Infrastructure**: Eloquent model and repository classes implement database persistence without coupling the domain to Eloquent.
- **Tests**: An in-memory repository implementation supports isolated and deterministic tests.

Routes are versioned under `/api/v1` and follow RESTful conventions:

| Method   | Path                 | Description     |
| -------- | -------------------- | --------------- |
| `GET`    | `/api/v1/users`      | List all users  |
| `POST`   | `/api/v1/users`      | Create a user   |
| `GET`    | `/api/v1/users/{id}` | Retrieve a user |
| `PUT`    | `/api/v1/users/{id}` | Update a user   |
| `DELETE` | `/api/v1/users/{id}` | Delete a user   |

This API is provided as a reference and starting point. Its responsibility-based folders can accommodate additional models and use cases, and projects can introduce feature-level grouping later if their size warrants it.

## Configuration

Application configuration and bootstrapping are organised inside the `bootstrap/` directory. Each file is responsible for a specific part of the application setup.

- **app.php**: Creates and configures the Slim application instance, including environment loading, container setup, middleware registration, error handling, and route registration.
- **environment.php**: Loads environment variables from the appropriate dotenv file.
- **settings.php**: Defines application configuration values such as environment mappings, error handling behaviour, logging settings, and CORS configuration.
- **dependencies.php**: Registers services in the dependency injection container, including infrastructure services and shared application components.
- **repositories.php**: Maps domain interfaces to their concrete implementations, keeping the domain layer decoupled from infrastructure.
- **database.php**: Boots Eloquent after the dependency injection container has been built.
- **middleware.php**: Configures the HTTP middleware pipeline, including cross-cutting concerns applied to incoming requests.
- **routes.php**: Defines HTTP routes and maps them to application actions, typically grouped and versioned.

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
