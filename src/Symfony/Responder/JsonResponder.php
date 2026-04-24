<?php

declare(strict_types=1);

namespace Vyse\Framework\Symfony\Responder;

use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class JsonResponder
{
    /**
     * @param array<mixed>|object $data The data to encode as JSON.
     * @param array<string, string|array<string>> $headers
     */
    public function __invoke(
        array | object $data = [],
        int $status = 200,
        array $headers = [],
    ): JsonResponse {
        return new JsonResponse($data, $status, $headers);
    }
}
