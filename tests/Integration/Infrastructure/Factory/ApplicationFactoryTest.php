<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Factory;

use App\Infrastructure\Application;
use App\Infrastructure\Factory\ApplicationFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\AbstractIntegrationTestCase;

/**
 * Unit tests for ApplicationFactory.
 */
final class ApplicationFactoryTest extends AbstractIntegrationTestCase
{
    /**
     * Asserts that the application factory creates an application instance.
     */
    public function testCreatesApplicationInstance(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/');

        $application = ApplicationFactory::create($request);

        $this->assertInstanceOf(Application::class, $application);
    }

    /**
     * Asserts that the application processes a request successfully.
     */
    public function testProcessesRequestSuccessfully(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/');

        $application = ApplicationFactory::create($request);

        $response = $application->handle($request);

        $this->assertNotNull($response);
    }
}
