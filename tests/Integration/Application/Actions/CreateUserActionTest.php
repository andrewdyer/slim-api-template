<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Actions;

/**
 * Integration tests for CreateUserAction.
 */
final class CreateUserActionTest extends AbstractUserActionTestCase
{
    /**
     * Asserts that a 201 response with the created user data is returned when valid input is provided.
     */
    public function testReturns201WhenDataIsValid(): void
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $email = $this->faker->unique()->safeEmail();

        $response = $this->request('POST', '/api/v1/users', [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
        ]);

        $this->assertSame(201, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertIsInt($data['id']);
        $this->assertSame($firstName, $data['firstName']);
        $this->assertSame($lastName, $data['lastName']);
        $this->assertSame($email, $data['email']);
    }

    /**
     * Asserts that a 500 response is returned when the request body is missing required fields.
     */
    public function testReturns500WhenRequiredFieldsAreMissing(): void
    {
        $response = $this->request('POST', '/api/v1/users', []);

        $this->assertSame(500, $response->getStatusCode());
    }
}
