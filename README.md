# Backend Template

A template for building modern backend REST APIs using the [Slim Framework](https://www.slimframework.com/).

## ✨ Introduction

This template provides a clean, opinionated foundation for building REST APIs with Slim Framework 4. It follows an Action--Domain--Responder (ADR) style architecture, separating Application, Domain, and Infrastructure concerns to promote maintainability, scalability, and clear boundaries between layers, while still remaining flexible enough to adapt to your own preferred structure and development approach.

## 📋 Prerequisites

- **[PHP](https://www.php.net/)**: Version 8.2 or higher is required.
- **[Composer](https://getcomposer.org/)**: Dependency management tool for PHP.

## 🏗️ Structure

This project follows an ADR-style architecture, separating Application, Domain, and Infrastructure concerns. The structure is intentionally simple and flexible, allowing you to adapt or restructure it to suit your own development style.

```plaintext
repo/
├── app/
│   ├── Application/        # Actions, DTOs, services
│   ├── Domain/             # Entities and interfaces
│   ├── Infrastructure/     # Implementations (e.g. persistence)
│   └── helpers.php
│
├── bootstrap/              # App wiring and configuration
│   ├── app.php
│   ├── dependencies.php
│   ├── environment.php
│   ├── repositories.php
│   ├── routes.php
│   └── settings.php
│
├── public/                 # Web entry point
│   └── index.php
│
├── tests/                  # Test suites
│
├── composer.json
└── .env.example
```

### 👤 Example: Users Feature

The template includes a simple **Users** feature to demonstrate how a vertical slice of the application can be structured using the ADR approach.

This example includes:

- an Action (HTTP entry point)
- a Service (application logic)
- a DTO (data transfer)
- a Repository interface and implementation

Routes are versioned under `/api/v1` and follow RESTful conventions:

| Method   | Path                 | Description     |
| -------- | -------------------- | --------------- |
| `GET`    | `/api/v1/users`      | List all users  |
| `POST`   | `/api/v1/users`      | Create a user   |
| `GET`    | `/api/v1/users/{id}` | Retrieve a user |
| `PUT`    | `/api/v1/users/{id}` | Update a user   |
| `DELETE` | `/api/v1/users/{id}` | Delete a user   |

This feature is provided as a reference only and can be safely removed when starting your own project.

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
