# Slim App Template

A template for building backend applications using the [Slim PHP framework](https://www.slimframework.com/docs/v4/).

## License
Licensed under the [MIT license](https://opensource.org/licenses/MIT). Totally free for private or commercial projects.

## Project Setup

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

## Project Structure

The project is organized for clarity, modularity, and separation of concerns.

The core application logic, such as controllers, resides in the `app/` directory. Configuration and initialization files for the application, container, environment, middleware, and controllers are organized in `bootstrap/`. Publicly accessible files, like the entry point and assets, are located in `public/`. Route definitions can be found in `routes/`, while logs and cached files are stored in `storage/`. The `tests/` directory holds unit and feature tests to ensure code quality. Additionally, the `workbench/` folder serves as a sandbox for experimentation and development.

Here’s a breakdown of the directory structure:

```text
my-project/
├── app/
├── bootstrap/
├── public/
├── routes/
├── storage/
├── tests/
└── workbench/
```
## Dockerizing

### `docker build -t slim-app-template .`

Builds a Docker image for the application.

### `docker run --name my-app -p 8888:80 -d slim-app-template`

Runs a container from the previously built Docker image.