<?php

declare(strict_types=1);

namespace Test\Unit\Responder;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Symfony\Responder\RedirectResponder;

final class RedirectResponderTest extends TestCase
{
    private RedirectResponder $responder;

    protected function setUp(): void
    {
        $this->responder = new RedirectResponder();
    }

    public function testInvokeWithDefaultStatus(): void
    {
        $url = 'https://example.com/dashboard';

        $response = ($this->responder)($url);

        self::assertSame($url, $response->getTargetUrl());
        self::assertSame(302, $response->getStatusCode());
    }

    public function testInvokeWithCustomStatus(): void
    {
        $url = '/temporary-path';
        $customStatus = 301;

        $response = ($this->responder)($url, $customStatus);

        self::assertSame($url, $response->getTargetUrl());
        self::assertSame($customStatus, $response->getStatusCode());
    }
}
