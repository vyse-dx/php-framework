<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler\Pass;

class TailwindAttributeMergePass
{
    /**
     * Finds class attributes placed next to {{ attributes }} and merges them
     * using the tailwind_merge filter to prevent class clashes.
     */
    public function __invoke(
        string $code,
    ): string {
        return preg_replace_callback(
            '/class="(.*?)"\s*\{\{\s*attributes\s*\}\}/',
            function (
                array $matches,
            ): string {
                $baseClass = trim($matches[1]);

                // Determine if the class is already a Twig expression or a static string
                $twigExpr = preg_match('/^\{\{\s*(.*?)\s*\}\}$/', $baseClass, $varMatches) === 1
                    ? $varMatches[1]
                    : "'" . addslashes($baseClass) . "'"
                ;

                return sprintf(
                    'class="{{ (%s ~ \' \' ~ attributes.render(\'class\'))|tailwind_merge|trim }}" {{ attributes }}',
                    $twigExpr,
                );
            },
            $code,
        ) ?? $code;
    }
}
