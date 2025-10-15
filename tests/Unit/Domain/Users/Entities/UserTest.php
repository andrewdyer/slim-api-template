<?php

namespace Tests\Unit\Domain\Users\Entities;

use App\Domain\Users\Entities\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    /**
     * @dataProvider userProvider
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
     * @dataProvider userProvider
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
