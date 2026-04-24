<?php

declare(strict_types=1);

namespace Vyse\Framework\Symfony\Responder;

use Symfony\Component\HttpFoundation\Response;
use Vyse\Framework\Symfony\Template\Render;

final readonly class ViewResponder
{
    public function __construct(
        private Render $render,
    ) {
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function __invoke(
        string $template,
        array $parameters = [],
        int $status = 200,
    ): Response {
        // Appends .html here so the caller only passes the base name
        $content = ($this->render)($template . '.html', $parameters);

        return new Response($content, $status);
    }
}
