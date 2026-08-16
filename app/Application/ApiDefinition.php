<?php

declare(strict_types=1);

namespace App\Application;

use OpenApi\Attributes as OA;

/**
 * Declares the root OpenAPI document metadata scanned by zircote/swagger-php.
 */
#[OA\Info(
    version: '1.0.0',
    description: 'REST API built on the Slim API Template.',
    title: 'Slim API Template'
)]
#[OA\Server(url: '/api/v1', description: 'Versioned API root.')]
final class ApiDefinition
{
}
