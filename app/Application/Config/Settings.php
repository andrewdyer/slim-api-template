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
        protected array $data,
    ) {
    }

    /**
     * Retrieve settings data.
     *
     * This method returns the full settings array when $key is null. When
     * $key is provided, it returns the corresponding value or null if the
     * key does not exist.
     *
     * @param string|null $key Optional top-level key to retrieve
     *
     * @return mixed Returns the full settings array when $key is null, otherwise the value or null
     */
    public function get(?string $key = null): mixed
    {
        if (null === $key) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
    }
}
