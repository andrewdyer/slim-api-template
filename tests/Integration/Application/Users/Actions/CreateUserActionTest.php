<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\AbstractIntegrationTestCase;

/**
 * Integration tests for CreateUserAction.
 */
class CreateUserActionTest extends AbstractIntegrationTestCase
{
    /**
     * Asserts that a 201 response with the created user data is returned when valid input is provided.
     */
    public function testReturns201WhenDataIsValid(): void
    {
        $response = $this->request('POST', '/api/v1/users', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $this->assertSame(201, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $user = $body['data'];
        $this->assertIsInt($user['id']);
        $this->assertSame('Jane', $user['firstName']);
        $this->assertSame('Doe', $user['lastName']);
        $this->assertSame('jane@example.com', $user['email']);
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
