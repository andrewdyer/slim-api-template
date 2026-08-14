<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\Repositories\PermissionRepositoryInterface;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;

/**
 * Intercepts requests to reject callers who lack the configured permission.
 *
 * Must run after authentication middleware that attaches the authenticated
 * user's ID to the request under the USER_ID_ATTRIBUTE attribute. Build
 * instances of this middleware through RequirePermissionMiddlewareFactory
 * rather than constructing it directly from a route definition.
 */
final readonly class RequirePermissionMiddleware implements MiddlewareInterface
{
    /**
     * Creates a new RequirePermissionMiddleware with the required dependencies.
     *
     * @param PermissionRepositoryInterface $permissions The repository used to resolve a user's permissions.
     * @param string                        $permission  The name of the permission required to proceed.
     */
    public function __construct(
        private PermissionRepositoryInterface $permissions,
        private string $permission,
    ) {
    }

    /**
     * Processes the request, rejecting callers who lack the required permission.
     *
     * @param  ServerRequestInterface  $request The incoming server request.
     * @param  RequestHandlerInterface $handler The next request handler.
     * @return ResponseInterface       The response from the next handler.
     * @throws LogicException          If the authenticated user's ID is unavailable on the request.
     * @throws HttpForbiddenException  If the authenticated user lacks the required permission.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userId = $request->getAttribute(self::USER_ID_ATTRIBUTE);

        if (!is_int($userId)) {
            throw new LogicException('The authenticated user ID is unavailable on the request.');
        }

        if (!in_array($this->permission, $this->permissions->findNamesForUser($userId), true)) {
            throw new HttpForbiddenException($request, "The {$this->permission} permission is required.");
        }

        return $handler->handle($request);
    }

    /**
     * The request attribute expected to hold the authenticated user's ID.
     */
    public const string USER_ID_ATTRIBUTE = 'user_id';
}
