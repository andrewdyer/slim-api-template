<?php

namespace Tests\Unit\Domain\Users\Entities;

use App\Domain\Users\Entities\User;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the User domain entity.
 *
 * This test class verifies the behavior of the User entity, including
 * getter methods, JSON serialization, and immutability constraints.
 */
final class UserTest extends TestCase
{
    /**
     * Test that User entity getters return the correct values.
     *
     * This test verifies that all getter methods on the User entity return
     * the values that were provided during construction. It uses a data
     * provider to test multiple user instances with different data.
     *
     * @dataProvider userProvider
     *
     * @param int    $id        User's unique identifier
     * @param string $email     User's email address
     * @param string $firstName User's first name
     * @param string $lastName  User's last name
     *
     * @return void
     */
    public function testGetters(int $id, string $email, string $firstName, string $lastName)
    {
        $user = new User($id, $firstName, $lastName, $email);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($email, $user->getEmail());
    }

    /**
     * Test that User entity JSON serialization produces expected output.
     *
     * This test verifies that the JsonSerializable implementation correctly
     * converts the User entity to a JSON-encodable array with the expected
     * structure and field names.
     *
     * @dataProvider userProvider
     *
     * @param int    $id        User's unique identifier
     * @param string $email     User's email address
     * @param string $firstName User's first name
     * @param string $lastName  User's last name
     *
     * @return void
     */
    public function testJsonSerialize(int $id, string $email, string $firstName, string $lastName)
    {
        $user = new User($id, $firstName, $lastName, $email);

        $expectedPayload = json_encode([
            'id' => $id,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }

    /**
     * Provide test data for User entity tests.
     *
     * This data provider supplies various user data combinations for testing
     * the User entity with different realistic values.
     *
     * @return array<int, array<int, string|int>> Array of user test data
     */
    public function userProvider(): array
    {
        return [
            [1, 'Bill', 'Gates', 'billgates@example.com'],
            [2, 'Steve', 'Jobs', 'stevejobs@example.com'],
            [3, 'Mark', 'Zuckerberg', 'markzuckerberg@example.com'],
            [4, 'Evan', 'Spiegel', 'evanspiegel@example.com'],
            [5, 'Jack', 'Dorsey', 'jackdorsey@example.com'],
        ];
    }
}
