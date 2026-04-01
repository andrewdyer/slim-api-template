# Slim API Template

A template for building modern backend REST APIs using the [Slim Framework](https://www.slimframework.com/).

## ✨ Introduction

This template provides a clean, opinionated foundation for building REST APIs with Slim Framework 4. It follows an Action--Domain--Responder (ADR) style architecture, separating Application, Domain, and Infrastructure concerns to promote maintainability, scalability, and clear boundaries between layers, while still remaining flexible enough to adapt to your own preferred structure and development approach.

## 📋 Prerequisites

Before you begin, ensure you have met the following requirements:

- **[PHP](https://www.php.net/)**: Version 8.3 or higher is required.
- **[Composer](https://getcomposer.org/)**: Dependency management tool for PHP.
- **[Docker](https://www.docker.com/)** (optional): For containerizing the application.

## 🏗️ Structure

This project follows an ADR-style architecture, organising code by responsibility:

- **Application**: HTTP layer and use-case coordination
- **Domain**: Core business logic and contracts
- **Infrastructure**: External integrations and implementations

The structure is intentionally simple and slice-friendly, allowing features to grow vertically without blurring boundaries between layers.

```plaintext
repo/
├── app/                         # PSR-4 Autoloaded logic
│   ├── Application/             # Request handling & orchestration
│   ├── Domain/                  # Business logic & Interface contracts
│   ├── Infrastructure/          # Database & third-party implementations
│   └── helpers.php              # Global utility functions
│
├── bootstrap/                   # Dependency Injection & Wiring
│   ├── app.php                  # App factory and middleware pipeline
│   ├── dependencies.php         # Container service registrations
│   ├── environment.php          # ENV loading logic
│   ├── repositories.php         # Interface → Implementation bindings
│   ├── routes.php               # Slim route definitions
│   └── settings.php             # Configuration arrays
│
├── public/                      # Web server document root
│   └── index.php                # HTTP entry point
│
├── storage/                     # File-based storage
│   └── logs/                    # Monolog output
│
├── tests/                       # PHPUnit Test Suite
│   ├── Integration/             # Tests covering the full HTTP stack
│   └── Unit/                    # Isolated logic tests
│
├── workbench/                   # Scratchpad for local experimentation
│
├── composer.json
├── Dockerfile
└── phpunit.xml
```

The template includes a simple **Users** feature to demonstrate how a vertical slice can be structured using the ADR approach. This example shows how a single feature can be organized across all layers:

- **Actions**: Create, delete, list, show and update HTTP entry points.
- **DTOs**: Data transfer objects for input and output.
- **Service**: Application logic orchestrated in a dedicated service class.
- **Domain**: Entity and repository contract define the business model and persistence interface.
- **Infrastructure**: In-memory repository implementation for demonstration purposes.

Routes are versioned under `/api/v1` and follow RESTful conventions:

| Method   | Path                 | Description     |
| -------- | -------------------- | --------------- |
| `GET`    | `/api/v1/users`      | List all users  |
| `POST`   | `/api/v1/users`      | Create a user   |
| `GET`    | `/api/v1/users/{id}` | Retrieve a user |
| `PUT`    | `/api/v1/users/{id}` | Update a user   |
| `DELETE` | `/api/v1/users/{id}` | Delete a user   |

This feature is provided as a reference and starting point—you can use it as a template for your own features, or remove it entirely when beginning your project.

## 🧰 Tooling

Essential development tools configured for a clean and consistent developer experience, including:

- [Slim Framework](https://www.slimframework.com/) as the HTTP micro-framework.
- [PHP-DI](https://php-di.org/) for dependency injection via a PSR-11 container.
- [Monolog](https://seldaek.github.io/monolog/) for PSR-3 compliant logging.
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) for environment variable management.
- [PHPUnit](https://phpunit.de/) for unit and integration testing.
- [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) for enforcing consistent code style.

## 🚀 Getting Started

If you like what you've seen so far and think this setup fits your needs, you can quickly get started by clicking the **Use this template** button at the top of the repository on GitHub.

## ⚖️ License

Licensed under the [MIT license](https://opensource.org/licenses/MIT) and is free for private or commercial projects.
