<?php

namespace App\Application\Http\Actions;

use AndrewDyer\Actions\AbstractAction;
use AndrewDyer\Actions\Payloads\ActionPayload;
use Psr\Http\Message\ResponseInterface as Response;

class IndexAction extends AbstractAction
{
    protected function handle(): Response
    {
        $payload = ActionPayload::success(['message' => 'Hello, world!']);

        return $this->respondWithJson($payload);
    }
}
