<?php

if (!function_exists('base_path')) {
    function base_path($path = ''): string
    {
        return __DIR__ . '/..//' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('get_env')) {
    function get_env($key, $default = null)
    {
        if (isset($_ENV[$key])) {
            $value = $_ENV[$key];

            switch (strtolower($value)) {
                case 'true':
                    return true;

                case 'false':
                    return false;

                default:
                    return $value;
            }
        }

        return $default;
    }
}

if (!function_exists('require_from_root')) {
    function require_from_root(string $path) {
        $full = base_path($path);
        if (!file_exists($full)) {
            throw new RuntimeException("File {$full} not found");
        }
        return require $full;
    }
}