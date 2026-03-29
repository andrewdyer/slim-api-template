<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require_from_root('bootstrap/environment.php')('.env');

$containerBuilder = new ContainerBuilder();

require_from_root('bootstrap/dependencies.php')($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//$app = new Slim\App([
//    'settings' => [
//        'displayErrorDetails' => (bool) get_env('APP_DEBUG', false),
//        'app' => [
//            'name' => get_env('APP_NAME', 'Skeleton'),
//            'env' => get_env('APP_ENV', 'production'),
//            'key' => get_env('APP_KEY'),
//            'url' => get_env('APP_URL', 'https://localhost:8888'),
//        ],
//        'logger' => [
//            'name' => get_env('LOGGER_NAME'),
//            'formatter' => [
//                'format' => get_env('LOGGER_FORMAT'),
//                'dateFormat' => get_env('LOGGER_DATE_FORMAT'),
//                'allowInlineLineBreaks' => get_env('LOGGER_ALLOW_INLINE_LINE_BREAKS'),
//                'ignoreEmptyContextAndExtra' => get_env('LOGGER_IGNORE_EMPTY_CONTEXT_AND_EXTRA'),
//            ],
//            'handler' => [
//                'level' => Monolog\Logger::DEBUG,
//            ],
//        ],
//    ],
//]);

require_from_root('bootstrap/routes.php')($app);
