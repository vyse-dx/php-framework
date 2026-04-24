<?php

declare(strict_types=1);

namespace Vyse\Framework\Symfony\Responder;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

final readonly class RouteResponder
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function __invoke(
        string $route,
        array $parameters = [],
        int $status = 302,
    ): RedirectResponse {
        $url = $this->router->generate($route, $parameters);

        return new RedirectResponse($url, $status);
    }
}
