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

Core application logic, such as controllers and services, resides in the `app/` directory. Configuration and bootstrap files are in `bootstrap/`, while publicly accessible files are in `public/`.

Route definitions are located in `routes/`. Logs and cached files are stored in `storage/`.

The `tests/` directory contains unit and feature tests.

Additionally, the `workbench/` folder can be used as a sandbox for testing and development purposes.

Here’s a breakdown of the directory structure:

```text
my-project/
├── app/
├── bootstrap/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
└── workbench/
```
