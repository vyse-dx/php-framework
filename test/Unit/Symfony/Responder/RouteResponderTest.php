<?php

declare(strict_types=1);

namespace Test\Unit\Responder;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Vyse\Framework\Symfony\Responder\RouteResponder;

final class RouteResponderTest extends TestCase
{
    private MockObject & RouterInterface $routerMock;
    private RouteResponder $responder;

    protected function setUp(): void
    {
        $this->routerMock = $this->createMock(RouterInterface::class);
        $this->responder = new RouteResponder($this->routerMock);
    }

    public function testInvokeWithDefaultParameters(): void
    {
        $route = 'app_homepage';
        $expectedUrl = '/';

        // Assert the router is called exactly once with the default empty array
        $this->routerMock->expects(self::once())
            ->method('generate')
            ->with($route, [])
            ->willReturn($expectedUrl)
        ;

        $response = ($this->responder)($route);

        self::assertSame($expectedUrl, $response->getTargetUrl());
        self::assertSame(302, $response->getStatusCode());
    }

    public function testInvokeWithCustomParametersAndStatus(): void
    {
        $route = 'app_user_profile';
        $parameters = ['id' => 42];
        $expectedUrl = '/user/42';
        $customStatus = 301;

        // Assert the router is called exactly once with the custom parameters
        $this->routerMock->expects(self::once())
            ->method('generate')
            ->with($route, $parameters)
            ->willReturn($expectedUrl)
        ;

        $response = ($this->responder)($route, $parameters, $customStatus);

        self::assertSame($expectedUrl, $response->getTargetUrl());
        self::assertSame($customStatus, $response->getStatusCode());
    }
}
