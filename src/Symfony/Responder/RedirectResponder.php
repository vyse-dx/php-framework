<?php

declare(strict_types=1);

namespace Vyse\Framework\Symfony\Responder;

use Symfony\Component\HttpFoundation\RedirectResponse;

final readonly class RedirectResponder
{
    public function __invoke(
        string $url,
        int $status = 302,
    ): RedirectResponse {
        return new RedirectResponse($url, $status);
    }
}
