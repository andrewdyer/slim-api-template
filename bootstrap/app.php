<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require_from_root('bootstrap/environment.php')();

$container = require_from_root('bootstrap/container.php')();

AppFactory::setContainer($container);

require_from_root('bootstrap/controllers.php')($container);

$app = AppFactory::create();

require_from_root('bootstrap/middleware.php')($app);

return $app;
