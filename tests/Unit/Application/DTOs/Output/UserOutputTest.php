<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs\Output;

use App\Application\DTOs\Output\UserOutput;
use App\Domain\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UserOutput.
 */
final class UserOutputTest extends TestCase
{
    /**
     * Asserts that the DTO is correctly populated from a domain User entity.
     */
    public function testCreatesInstanceWhenDomainUserIsProvided(): void
    {
        $user = new User(1, 'Jane', 'Doe', 'jane@example.com');

        $output = UserOutput::fromDomain($user);

        $this->assertSame(1, $output->id);
        $this->assertSame('Jane', $output->firstName);
        $this->assertSame('Doe', $output->lastName);
        $this->assertSame('jane@example.com', $output->email);
    }

    /**
     * Asserts that jsonSerialize returns an associative array with the expected keys and values.
     */
    public function testReturnsAssociativeArrayWhenSerialized(): void
    {
        $output = new UserOutput(2, 'John', 'Smith', 'john@example.com');

        $this->assertSame([
            'id' => 2,
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'john@example.com',
        ], $output->jsonSerialize());
    }
}
