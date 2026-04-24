<?php

declare(strict_types=1);

namespace Vyse\Framework\Symfony\Template;

use Twig\Environment;

final readonly class Render
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function __invoke(
        string $template,
        array $parameters = [],
    ): string {
        return $this->twig->render($template . '.twig', $parameters);
    }
}
