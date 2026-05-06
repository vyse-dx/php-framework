<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler\Pass;

class ComponentTagConversionPass
{
    /**
     * Converts standard uppercase component tags to Twig namespaced tags.
     * Example: <Submit> becomes <twig:Submit>
     */
    public function __invoke(
        string $code,
    ): string {
        return preg_replace(
            [
                '/<([A-Z][a-zA-Z0-9]*)([^>]*)>/',
                '/<\/([A-Z][a-zA-Z0-9]*)\s*>/'
            ],
            [
                '<twig:$1$2>',
                '</twig:$1>'
            ],
            $code,
        ) ?? $code;
    }
}
