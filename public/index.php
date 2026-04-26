<?php

declare(strict_types=1);

use App\Infrastructure\Factory\ApplicationFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$serverRequestCreator = ServerRequestCreatorFactory::create();

$request = $serverRequestCreator->createServerRequestFromGlobals();

$application = ApplicationFactory::create($request);

$application->run($request);
