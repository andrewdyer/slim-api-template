<?php

declare(strict_types=1);

namespace App\Application\Config;

/**
 * Simple configuration container for application settings.
 *
 * This immutable value object holds configuration data and provides a
 * convenient accessor to retrieve either the whole configuration array
 * or individual values by key.
 */
final readonly class Settings
{
    /**
     * Construct a new Settings instance.
     *
     * @param array<string,mixed> $data The settings array to be exposed by this instance
     */
    public function __construct(
        private array $data,
    ) {
    }

    /**
     * Retrieve all configuration settings.
     *
     * This method returns the entire settings array.
     *
     * @return array<string,mixed> The full configuration data
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Retrieve a configuration value by key.
     *
     * This method returns the value for the provided $key. If the key does
     * not exist in the settings, it will return $default if provided;
     * otherwise, it throws an InvalidArgumentException.
     *
     * @param string     $key     The top-level key to retrieve
     * @param mixed|null $default Optional default value to return if key is missing
     *
     * @return mixed The value associated with the key, or $default if provided
     *
     * @throws \InvalidArgumentException If the key does not exist and no default is provided
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (func_num_args() === 2) {
            return $default;
        }

        throw new \InvalidArgumentException("Configuration key '{$key}' not found.");
    }

    /**
     * Check for the existence of a top-level key in the settings array.
     *
     * This method determines whether the specified $key exists in the settings.
     * Keys set to null are considered present since array_key_exists is used
     * for the check.
     *
     * @param string $key Top-level key to check for existence
     *
     * @return bool True if the key exists in the settings array, otherwise false
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }
}
