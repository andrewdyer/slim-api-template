<?php

use DI\Container;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require_from_root('bootstrap/environment.php')();

$container = new Container();

AppFactory::setContainer($container);

require_from_root('bootstrap/settings.php')($container);

require_from_root('bootstrap/database.php')($container);

require_from_root('bootstrap/controllers.php')($container);

require_from_root('bootstrap/services.php')($container);

$app = AppFactory::create();

// Automatically parse JSON, form, and multipart bodies into $request->getParsedBody()
$app->addBodyParsingMiddleware();

require_from_root('bootstrap/middleware.php')($app);

return $app;
