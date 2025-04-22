<?php

namespace App\Services;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

readonly class LoggerService
{
    public function __construct(private array $settings = [])
    {
    }

    public function __invoke(): Logger
    {
        $logger = new Logger($this->settings['name']);

        $handler = new RotatingFileHandler(
            root_path('storage/logs/app.log'),
            $this->settings['max_files'],
            $this->settings['level']
        );

        $formatter = new LineFormatter(
            "[%datetime%] %level_name%: %message% %context% %extra%\n",
            'Y-m-d H:i:s'
        );

        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    }
}
