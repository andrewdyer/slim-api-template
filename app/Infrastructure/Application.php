<?php

declare(strict_types=1);

namespace App\Infrastructure;

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

/**
 * Application wrapper responsible for handling requests and emitting responses.
 */
final readonly class Application
{
    /**
     * Handles HTTP request processing.
     */
    private App $app;

    /**
     * Emits HTTP responses with CORS headers applied.
     */
    private CorsResponseEmitter $emitter;

    /**
     * Creates the application wrapper.
     *
     * Initializes the wrapper with the Slim application and response emitter.
     *
     * @param  App                 $app     The Slim application instance.
     * @param  CorsResponseEmitter $emitter The response emitter.
     * @return void                Returns after the application is constructed.
     */
    public function __construct(App $app, CorsResponseEmitter $emitter)
    {
        $this->app = $app;
        $this->emitter = $emitter;
    }

    /**
     * Processes the incoming request and returns the response.
     *
     * @param  ServerRequestInterface $request The incoming server request.
     * @return ResponseInterface      Returns the application response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->app->handle($request);
    }

    /**
     * Processes the incoming request and emits the response.
     *
     * @param  ServerRequestInterface $request The incoming server request.
     * @return void                   Returns after the response is emitted.
     */
    public function run(ServerRequestInterface $request): void
    {
        $this->emitter->emit(
            $this->handle($request)
        );
    }

    /**
     * Returns the underlying Slim application instance.
     *
     * @return App Returns the Slim application.
     */
    public function getApp(): App
    {
        return $this->app;
    }
}
