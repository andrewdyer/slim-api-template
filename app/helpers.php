<?php

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
        if (array_key_exists($key, $_ENV)) {
            $value = $_ENV[$key];

            if ($value === 'true' || $value === 'TRUE') {
                return true;
            }

            if ($value === 'false' || $value === 'FALSE') {
                return false;
            }

            return $value;
        }

        return $default;
    }
}
