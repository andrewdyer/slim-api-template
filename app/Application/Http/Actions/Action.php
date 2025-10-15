<?php

declare(strict_types=1);

namespace App\Application\Http\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

/**
 * Base class for all HTTP action handlers.
 *
 * This abstract class provides common functionality for processing HTTP requests
 * in a Slim framework application. It follows the Action-Domain-Responder (ADR)
 * pattern, where each action is responsible for handling a specific HTTP endpoint.
 */
abstract class Action
{
    /**
     * Route arguments extracted from the URL.
     *
     * @var array<string, string|int>
     */
    protected array $args = [];

    /**
     * The current HTTP request object.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * The HTTP response object to be returned.
     *
     * @var Response
     */
    protected Response $response;

    /**
     * Handle the HTTP request and return a response.
     *
     * This method is called by the Slim framework when the action is invoked.
     * It sets up the internal state and delegates to the abstract action method.
     *
     * @param Request                   $request  The HTTP request object
     * @param Response                  $response The HTTP response object
     * @param array<string, string|int> $args     Route arguments from the URL
     *
     * @return Response The processed HTTP response
     */
    final public function __invoke(
        Request $request,
        Response $response,
        array $args,
    ): Response {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    /**
     * Execute the specific action logic.
     *
     * This method must be implemented by concrete action classes to define
     * their specific behavior for handling the HTTP request.
     *
     * @return Response The HTTP response to return to the client
     */
    abstract protected function action(): Response;

    /**
     * Get the parsed request body as an array.
     *
     * This method safely extracts and converts the request body to an array,
     * handling cases where the body might be null or a different type.
     *
     * @return array<string, mixed> The parsed request body data
     */
    protected function getParsedBody(): array
    {
        $data = $this->request->getParsedBody() ?? [];

        return is_array($data) ? $data : (array) $data;
    }

    /**
     * Extract a required route argument by name.
     *
     * This method retrieves a route argument that was captured from the URL
     * pattern. If the argument is missing, it throws an exception.
     *
     * @param string $name The name of the route argument to retrieve
     *
     * @return string|int The value of the route argument
     *
     * @throws HttpBadRequestException If the required argument is missing
     */
    protected function resolveArg(string $name): string|int
    {
        if (!array_key_exists($name, $this->args)) {
            throw new HttpBadRequestException($this->request, "Missing route argument: {$name}");
        }

        return $this->args[$name];
    }
}
