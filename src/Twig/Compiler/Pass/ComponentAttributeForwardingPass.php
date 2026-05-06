<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler\Pass;

class ComponentAttributeForwardingPass
{
    /**
     * Converts {{ attributes }} to :attributes="attributes" for nested component tags.
     */
    public function __invoke(
        string $code,
    ): string {
        return preg_replace(
            '/(<twig:[A-Z][a-zA-Z0-9]*[^>]*?)\{\{\s*attributes\s*\}\}/',
            '$1:attributes="attributes"',
            $code,
        ) ?? $code;
    }
}
