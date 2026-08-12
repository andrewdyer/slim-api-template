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
     * Asserts that a 400 response is returned when the given email is not validly formatted.
     */
    public function testReturns400WhenEmailIsInvalid(): void
    {
        $response = $this->request('POST', '/api/v1/users', [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => 'not-an-email',
        ]);

        $this->assertSame(400, $response->getStatusCode());
    }

    /**
     * Asserts that a 400 response is returned when the request body is missing required fields.
     */
    public function testReturns400WhenRequiredFieldsAreMissing(): void
    {
        $response = $this->request('POST', '/api/v1/users', []);

        $this->assertSame(400, $response->getStatusCode());
    }

    /**
     * Asserts that a 409 response is returned when the given email is already in use.
     */
    public function testReturns409WhenEmailAlreadyExists(): void
    {
        $existingUser = $this->userFactory->create();

        $response = $this->request('POST', '/api/v1/users', [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $existingUser->getEmail(),
        ]);

        $this->assertSame(409, $response->getStatusCode());
    }
}
