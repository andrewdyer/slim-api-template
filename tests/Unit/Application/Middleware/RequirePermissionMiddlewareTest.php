<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Middleware;

use App\Application\Middleware\RequirePermissionMiddleware;
use LogicException;
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
 * Unit tests for RequirePermissionMiddleware.
 */
#[CoversClass(RequirePermissionMiddleware::class)]
final class RequirePermissionMiddlewareTest extends TestCase
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
     * Asserts that a user holding the required permission is allowed through.
     */
    public function testAllowsAUserWithTheRequiredPermission(): void
    {
        $permissions = new InMemoryPermissionRepository();
        $permissions->grant(42, 'users.manage');

        $middleware = new RequirePermissionMiddleware($permissions, 'users.manage');
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/users')
            ->withAttribute(RequirePermissionMiddleware::USER_ID_ATTRIBUTE, 42);

        $response = $middleware->process($request, $this->handler());

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * Asserts that a missing authenticated user ID attribute is rejected.
     */
    public function testRejectsARequestMissingTheAuthenticatedUserId(): void
    {
        $middleware = new RequirePermissionMiddleware(new InMemoryPermissionRepository(), 'users.manage');
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/users');

        $this->expectException(LogicException::class);
        $middleware->process($request, $this->handler());
    }

    /**
     * Asserts that a user without the required permission is rejected.
     */
    public function testRejectsAUserWithoutTheRequiredPermission(): void
    {
        $permissions = new InMemoryPermissionRepository();
        $permissions->grant(42, 'users.view');

        $middleware = new RequirePermissionMiddleware($permissions, 'users.manage');
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/users')
            ->withAttribute(RequirePermissionMiddleware::USER_ID_ATTRIBUTE, 42);

        $this->expectException(HttpForbiddenException::class);
        $middleware->process($request, $this->handler());
    }
}
