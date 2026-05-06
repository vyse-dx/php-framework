<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler\Pass;

class RootAttributeInjectionPass
{
    /**
     * Auto-injects {{ attributes }} into the FIRST HTML tag of a component,
     * unless the attributes variable is already present or the root is a slot.
     */
    public function __invoke(
        string $code,
    ): string {
        if (str_contains($code, 'attributes')) {
            return $code;
        }

        $injected = false;

        return preg_replace_callback(
            '/<([a-zA-Z0-9:]+)([^>]*?)(\s*\/?)>/i',
            function (
                array $matches,
            ) use (&$injected): string {
                if ($injected) {
                    return $matches[0];
                }

                $injected = true;

                $tagName = $matches[1];

                if (str_starts_with($tagName, 'slot:') || $tagName === 'twig:block') {
                    return $matches[0];
                }

                return "<{$tagName}{$matches[2]} {{ attributes }}{$matches[3]}>";
            },
            $code,
        ) ?? $code;
    }
}
