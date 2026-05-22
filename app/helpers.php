<?php

if (!function_exists('root_path')) {
    /**
     * Returns the absolute path to the project root, optionally suffixed with the given path.
     *
     * @param  string $path Relative path to append to the root.
     * @return string The absolute path.
     */
    function root_path(string $path = ''): string
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
     * Retrieves an environment variable, casting "true" and "false" strings to booleans.
     *
     * @param  string $key     The environment variable key to retrieve.
     * @param  mixed  $default Value to return when the key is not set.
     * @return mixed
     */
    function get_env(string $key, mixed $default = null): mixed
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

if (!function_exists('get_env_array')) {
    /**
     * Returns an environment variable as an array, split by a delimiter.
     *
     * Empty values return an empty array.
     *
     * @param  string             $key       Environment variable name.
     * @param  string             $delimiter Delimiter used to split the string.
     * @return array<int, string>
     */
    function get_env_array(string $key, string $delimiter = ','): array
    {
        $value = get_env($key, '');

        if (!is_string($value) || $value === '') {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode($delimiter, $value)),
            static fn (string $item): bool => $item !== ''
        ));
    }
}

if (!function_exists('require_from_root')) {
    /**
     * Returns the result of requiring a file relative to the project root.
     *
     * @param  string            $path The relative path to the file.
     * @return mixed             The return value from the required file.
     * @throws \RuntimeException If the file does not exist.
     */
    function require_from_root(string $path): mixed
    {
        $full = root_path($path);

        if (!file_exists($full)) {
            throw new RuntimeException("File {$full} not found");
        }

        return require $full;
    }
}
