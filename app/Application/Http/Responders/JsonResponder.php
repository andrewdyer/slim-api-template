<?php

declare(strict_types=1);

namespace App\Application\Http\Responders;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles JSON response formatting and output.
 *
 * This responder provides a standardized way to return JSON responses
 * from HTTP actions. It handles JSON encoding, content-type headers,
 * and status codes consistently across the application.
 */
final class JsonResponder
{
    /**
     * Create a JSON response with the provided data and status code.
     *
     * This method encodes the provided data as JSON and writes it to the
     * response body. It also sets the appropriate content-type header and
     * status code.
     *
     * @param Response $response The PSR-7 response object to modify
     * @param mixed    $data     The data to encode as JSON and include in the response
     * @param int      $status   The HTTP status code for the response
     *
     * @return Response The modified response object with JSON content
     */
    public function respond(Response $response, mixed $data, int $status = 200): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->withStatus($status);
    }
}
