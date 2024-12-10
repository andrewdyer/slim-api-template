<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/environment.php';

$container = (require_once __DIR__ . '/container.php')();

AppFactory::setContainer($container);

(require_once __DIR__ . '/database.php')($container);

$app = AppFactory::create();

return $app;
