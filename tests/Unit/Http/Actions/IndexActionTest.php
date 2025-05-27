<?php

namespace Tests\Unit\Http\Actions;

use App\Http\Actions\IndexAction;
use App\Http\Responders\TwigResponder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IndexActionTest extends TestCase
{
    public function testItRendersTheIndexTemplate()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $expectedResponse = $this->createMock(ResponseInterface::class);

        $responder = $this->createMock(TwigResponder::class);
        $responder->expects($this->once())
            ->method('respond')
            ->with($response, 'index.html.twig', [
                'actionName' => 'IndexAction',
                'actionLocation' => 'app/Http/Actions/IndexAction.php',
            ])
            ->willReturn($expectedResponse);

        $action = new IndexAction($responder);

        $result = $action($request, $response);

        $this->assertSame($expectedResponse, $result);
    }
}
