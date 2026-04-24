<?php

declare(strict_types=1);

namespace Test\Unit\Responder;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Vyse\Framework\Symfony\Responder\ViewResponder;
use Vyse\Framework\Symfony\Template\Render;

final class ViewResponderTest extends TestCase
{
    private MockObject & Render $renderMock;
    private ViewResponder $responder;

    protected function setUp(): void
    {
        $this->renderMock = $this->createMock(Render::class);
        $this->responder = new ViewResponder($this->renderMock);
    }

    public function testInvokeReturnsResponseWithCorrectContentAndDefaultStatus(): void
    {
        $template = 'test/index';
        $expectedRenderTemplate = 'test/index.html';
        $parameters = ['key' => 'value'];
        $renderedContent = '<h1>Test Template</h1>';

        $this->renderMock->expects(self::once())
            ->method('__invoke')
            /** @phpstan-ignore method.nonObject */
            ->with($expectedRenderTemplate, $parameters)
            ->willReturn($renderedContent)
        ;

        $response = ($this->responder)($template, $parameters);

        self::assertSame($renderedContent, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    public function testInvokeAllowsCustomStatusCode(): void
    {
        $template = 'error/not_found';
        $expectedRenderTemplate = 'error/not_found.html';
        $renderedContent = 'Not Found';
        $customStatus = 404;

        $this->renderMock->expects(self::once())
            ->method('__invoke')
            /** @phpstan-ignore method.nonObject */
            ->with($expectedRenderTemplate, [])
            ->willReturn($renderedContent)
        ;

        $response = ($this->responder)($template, [], $customStatus);

        self::assertSame($renderedContent, $response->getContent());
        self::assertSame($customStatus, $response->getStatusCode());
    }
}
