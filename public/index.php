<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$appFactory = require __DIR__ . '/../bootstrap/app.php';

$app = $appFactory();

$app->run();