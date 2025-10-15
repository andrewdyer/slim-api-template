<?php

namespace App\Application\Http\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected array $args = [];
    protected Request $request;
    protected Response $response;

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

    abstract protected function action(): Response;

    protected function getParsedBody(): array
    {
        $data = $this->request->getParsedBody() ?? [];

        return is_array($data) ? $data : (array) $data;
    }

    protected function resolveArg(string $name): string|int
    {
        if (!array_key_exists($name, $this->args)) {
            throw new HttpBadRequestException($this->request, "Missing route argument: {$name}");
        }

        return $this->args[$name];
    }
}
