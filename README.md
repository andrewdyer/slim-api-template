# Slim App Template

A template for building backend applications using the [Slim PHP framework](https://www.slimframework.com/docs/v4/).

## License
Licensed under the [MIT license](https://opensource.org/licenses/MIT). Totally free for private or commercial projects.

## Introduction

This template is built with Slim version 4 and Slim PSR-7, and includes the following extended setup:

- [PHP-DI](https://php-di.org/) for dependency injection.
- [PHP dotenv](https://github.com/vlucas/phpdotenv) for environment configuration.
- [PHPUnit](https://phpunit.de/) for testing.
- [Monolog](https://seldaek.github.io/monolog/) for logging.
- [PHP Coding Standards Fixer](https://cs.symfony.com/) for code formatting.

## Project Structure

This project emphasizes clarity, modularity, and separation of concerns.

### `app/`
The core application logic, such as controllers, resides in this directory.

### `bootstrap/`
Configuration and initialization files for the application, container, environment, middleware, and controllers are organized here.

### `public/`
Publicly accessible files, like the entry point and assets, are located in this directory.

### `routes/`
Route definitions can be found in this directory.

### `storage/`
Logs and cached files are stored in this directory.

### `tests/`
This directory holds unit and feature tests to ensure code quality.

### `workbench/`
This folder serves as a sandbox for experimentation and development.

## Getting Started

### Install Dependencies

From the project root directory, run the following command to install required dependencies:

```shell
composer install
```

### Environment Configuration

To ensure correct environment configuration, copy the `.env.example` file to `.env` and update it with your settings.

```shell
cp .env.example .env
```

### Start the Web Server

Once installed, you can start a localhost web server by running the following command from the project root directory in terminal:

```shell
php -S localhost:8888 -t public public/index.php
```

## Dockerizing

### `docker build -t slim-app-template .`

Builds a Docker image for the application.

### `docker run --name my-app -p 8888:80 -d slim-app-template`

Runs a container from the previously built Docker image.