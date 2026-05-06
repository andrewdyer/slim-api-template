<?php

declare(strict_types=1);

use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Interfaces\ErrorHandlerInterface;

return function (App $app, ?LoggerInterface $logger = null): ErrorHandlerInterface {
    return new JsonErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory(),
        $logger
    );
};