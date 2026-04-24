<?php

declare(strict_types=1);

namespace Test\Unit\Template;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Vyse\Framework\Symfony\Template\Render;

final class RenderTest extends TestCase
{
    private MockObject & Environment $twigMock;
    private Render $render;

    protected function setUp(): void
    {
        $this->twigMock = $this->createMock(Environment::class);
        $this->render = new Render($this->twigMock);
    }

    public function testInvokeAppendsExtensionAndReturnsString(): void
    {
        $template = 'test/index.html';
        $parameters = ['controller_name' => 'TestController'];
        $renderedString = '<h1>Hello TestController!</h1>';

        // Assert Twig is called with the appended .twig extension
        $this->twigMock->expects(self::once())
            ->method('render')
            ->with($template . '.twig', $parameters)
            ->willReturn($renderedString)
        ;

        $output = ($this->render)($template, $parameters);

        self::assertSame($renderedString, $output);
    }
}
