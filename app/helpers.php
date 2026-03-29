<?php

if (!function_exists('root_path')) {
    function root_path($path = ''): string
    {
        $base = __DIR__ . DIRECTORY_SEPARATOR . '..';

        if ($path) {
            $path = ltrim($path, '/\\');

            return $base . DIRECTORY_SEPARATOR . $path;
        }

        return $base;
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

if (!function_exists('require_from_root')) {
    function require_from_root(string $path)
    {
        $full = root_path($path);

        if (!file_exists($full)) {
            throw new RuntimeException("File {$full} not found");
        }

        return require $full;
    }
}
