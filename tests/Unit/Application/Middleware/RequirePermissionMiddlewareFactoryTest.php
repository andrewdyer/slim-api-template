<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Middleware;

use App\Application\Middleware\RequirePermissionMiddleware;
use App\Application\Middleware\RequirePermissionMiddlewareFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Support\Persistence\Repositories\InMemoryPermissionRepository;

/**
 * Unit tests for RequirePermissionMiddlewareFactory.
 */
#[CoversClass(RequirePermissionMiddlewareFactory::class)]
final class RequirePermissionMiddlewareFactoryTest extends TestCase
{
    /**
     * Creates a handler that always responds successfully.
     *
     * @return RequestHandlerInterface The test request handler.
     * @internal
     */
    private function handler(): RequestHandlerInterface
    {
        return new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return (new ResponseFactory())->createResponse();
            }
        };
    }

    /**
     * Asserts that the middleware built by make requires the given permission.
     */
    public function testMakeConfiguresTheMiddlewareWithTheGivenPermission(): void
    {
        $permissions = new InMemoryPermissionRepository();
        $permissions->grant(42, 'users.view');

        $factory = new RequirePermissionMiddlewareFactory($permissions);
        $middleware = $factory->make('users.manage');

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/users')
            ->withAttribute(RequirePermissionMiddleware::USER_ID_ATTRIBUTE, 42);

        $this->expectException(HttpForbiddenException::class);
        $middleware->process($request, $this->handler());
    }

    /**
     * Asserts that make returns a RequirePermissionMiddleware instance.
     */
    public function testMakeReturnsARequirePermissionMiddlewareInstance(): void
    {
        $factory = new RequirePermissionMiddlewareFactory(new InMemoryPermissionRepository());

        $middleware = $factory->make('users.manage');

        $this->assertInstanceOf(RequirePermissionMiddleware::class, $middleware);
    }

    /**
     * Asserts that the middleware built by make is wired to the given permission repository.
     */
    public function testMakeWiresTheGivenPermissionRepositoryIntoTheMiddleware(): void
    {
        $permissions = new InMemoryPermissionRepository();
        $permissions->grant(42, 'users.manage');

        $factory = new RequirePermissionMiddlewareFactory($permissions);
        $middleware = $factory->make('users.manage');

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/users')
            ->withAttribute(RequirePermissionMiddleware::USER_ID_ATTRIBUTE, 42);

        $response = $middleware->process($request, $this->handler());

        $this->assertSame(200, $response->getStatusCode());
    }
}
