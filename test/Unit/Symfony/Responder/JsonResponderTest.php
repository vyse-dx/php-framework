<?php

declare(strict_types=1);

namespace Test\Unit\Responder;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vyse\Framework\Symfony\Responder\JsonResponder;

final class JsonResponderTest extends TestCase
{
    private JsonResponder $responder;

    protected function setUp(): void
    {
        $this->responder = new JsonResponder();
    }

    public function testInvokeReturnsEmptyJsonResponseByDefault(): void
    {
        $response = ($this->responder)();

        self::assertSame('[]', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    public function testInvokeEncodesArrayData(): void
    {
        $data = ['status' => 'success', 'id' => 42];
        $expectedJson = json_encode($data);

        $response = ($this->responder)($data);

        self::assertSame($expectedJson, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    public function testInvokeEncodesObjectData(): void
    {
        $data = new stdClass();
        $data->name = 'Test User';
        $data->active = true;

        $expectedJson = json_encode($data);

        $response = ($this->responder)($data);

        self::assertSame($expectedJson, $response->getContent());
    }

    public function testInvokeSetsCustomStatusCodeAndHeaders(): void
    {
        $data = ['error' => 'Not Found'];
        $status = 404;
        $headers = [
            'X-Custom-Header' => 'TestValue',
            'Cache-Control' => 'no-cache',
        ];

        $response = ($this->responder)($data, $status, $headers);

        self::assertSame(json_encode($data), $response->getContent());
        self::assertSame($status, $response->getStatusCode());

        // Symfony's Response headers are stored in a ResponseHeaderBag, which normalizes keys to lowercase
        self::assertSame('TestValue', $response->headers->get('x-custom-header'));

        // Symfony automatically appends 'private' to Cache-Control for safety if not explicitly declared public
        self::assertSame('no-cache, private', $response->headers->get('cache-control'));
    }
}
