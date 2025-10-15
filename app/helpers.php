<?php

declare(strict_types=1);

if (!function_exists('base_path')) {
    /**
     * Get the base path of the application with an optional sub-path.
     *
     * This function returns the absolute path to the application's root directory.
     * If a sub-path is provided, it will be appended with the proper directory
     * separator.
     *
     * @param string $path Optional sub-path to append to the base path
     *
     * @return string The absolute path to the application root or specified sub-path
     */
    function base_path($path = ''): string
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
    /**
     * Get an environment variable with optional default value and type casting.
     *
     * This function retrieves a value from the $_ENV superglobal with automatic
     * type conversion for boolean values. String values 'true' and 'false' are
     * converted to their respective boolean types.
     *
     * @param string $key     The environment variable key to retrieve
     * @param mixed  $default The default value to return if the key doesn't exist
     *
     * @return mixed The environment variable value with type conversion applied,
     *               or the default value if not found
     */
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
    /**
     * Require a file relative to the application's root directory.
     *
     * This function safely requires a file by building an absolute path from
     * the application root. It validates that the file exists before attempting
     * to require it, throwing an exception if the file is not found.
     *
     * @param string $path The relative path from the application root to the file
     *
     * @return mixed The return value of the required file
     *
     * @throws RuntimeException If the specified file does not exist
     */
    function require_from_root(string $path)
    {
        $full = base_path($path);
        if (!file_exists($full)) {
            throw new RuntimeException("File {$full} not found");
        }

        return require $full;
    }
}
