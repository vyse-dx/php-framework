<?php

declare(strict_types=1);

namespace Test\Integration\Symfony\Responder;

use PHPUnit\Framework\MockObject\MockObject;
use Twig\Environment;
use Vyse\Framework\Symfony\Responder\ViewResponder;
use Vyse\Toolchain\PhpUnit\TestCase\IntegrationTestCase;

final class ViewResponderTest extends IntegrationTestCase
{
    public function test_it_integrates_with_render_and_returns_response(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);

        $twigMock->expects(self::once())
            ->method('render')
            ->with('home.html.twig', ['title' => 'Test'])
            ->willReturn('<html>Test Content</html>')
        ;

        $responder = $this->make(ViewResponder::class, [
            Environment::class => $twigMock,
        ]);

        $response = $responder('home', ['title' => 'Test'], 201);

        self::assertSame('<html>Test Content</html>', $response->getContent());
        self::assertSame(201, $response->getStatusCode());
    }
}
