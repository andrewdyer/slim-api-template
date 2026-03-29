<?php

$container = $app->getContainer();

$container['foundHandler'] = function () {
    return new Slim\Handlers\Strategies\RequestResponseArgs();
};

$container['logger'] = function ($container) {
    $config = $container->get('settings')->get('logger');

    $formatter = new Monolog\Formatter\LineFormatter(
        $config['formatter']['format'] . "\n",
        $config['formatter']['dateFormat'],
        $config['formatter']['allowInlineLineBreaks'],
        $config['formatter']['ignoreEmptyContextAndExtra']
    );

    $handler = new Monolog\Handler\StreamHandler(
        base_path('storage/logs/app.log'),
        $config['handler']['level']
    );

    $handler->setFormatter($formatter);

    $logger = new Monolog\Logger($config['name']);
    $logger->pushHandler($handler);

    return $logger;
};
