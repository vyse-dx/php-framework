<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler\Pass;

class SlotOutputResolutionPass
{
    /**
     * Converts {{ slots.name }} outputs into native provider/consumer block logic.
     * Maps the "default" slot specifically to "content".
     */
    public function __invoke(
        string $code,
    ): string {
        return preg_replace_callback(
            '/\{\{\s*slots\.([a-zA-Z0-9_]+)\s*\}\}/',
            function (
                array $matches,
            ): string {
                $slotName = $matches[1] === 'default' ? 'content' : $matches[1];

                return sprintf(
                    '{%% block %1$s %%}{{ outerBlocks is defined and outerBlocks.%1$s != "outer__block_fallback" ? block(outerBlocks.%1$s) : "" }}{%% endblock %%}',
                    $slotName,
                );
            },
            $code,
        ) ?? $code;
    }
}
