<?php

use Psr\Http\Message\ResponseInterface;

if (!function_exists('root_path')) {
    function root_path($path = ''): string
    {
        return __DIR__ . '/..//' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('require_from_root')) {
    function require_from_root($path)
    {
        return require_once root_path($path);
    }
}

if (!function_exists('get_env')) {
    function get_env($key, $default = null)
    {
        if (isset($_ENV[$key])) {
            $value = $_ENV[$key];

            switch (strtolower($value)) {
                case 'true' === $value:
                    return true;

                case 'false' === $value:
                    return false;

                default:
                    return $value;
            }
        }

        return $default;
    }
}

if (!function_exists('get_env_bool')) {
    function get_env_bool($key, $default = false): bool
    {
        return filter_var(get_env($key, $default), FILTER_VALIDATE_BOOLEAN);
    }
}

if (!function_exists('get_env_int')) {
    function get_env_int($key, $default = 0): int
    {
        return (int)get_env($key, $default);
    }
}

if (!function_exists('json_response')) {
    function json_response(ResponseInterface $response, array $data, int $status = 200): ResponseInterface
    {
        $response->getBody()->write(json_encode($data));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
