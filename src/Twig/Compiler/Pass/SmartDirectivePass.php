<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler\Pass;

class SmartDirectivePass
{
    private const MAPPING = [
        // Alpine Core
        'model' => 'x-model',
        'show' => 'x-show',
        'data' => 'x-data',
        'ref' => 'x-ref',
        'text' => 'x-text',
        'html' => 'x-html',
        'bind' => 'x-bind',
        'cloak' => 'x-cloak',
        'transition' => 'x-transition',
        'init' => 'x-init',

        // HTMX
        'get' => 'hx-get',
        'post' => 'hx-post',
        'put' => 'hx-put',
        'patch' => 'hx-patch',
        'delete' => 'hx-delete',
        'trigger' => 'hx-trigger',
        'target' => 'hx-target',
        'swap' => 'hx-swap',
        'push-url' => 'hx-push-url',
    ];

    /**
     * Converts @shorthands to their full x- or hx- equivalents.
     * Safely ignores native Alpine events like @click or @focus.
     */
    public function __invoke(
        string $code,
    ): string {
        return preg_replace_callback(
            // Matches an @ symbol preceded by a space or <, followed by letters/hyphens and optional .modifiers
            '/(?<=[\s<])@([a-zA-Z0-9\-]+)((\.[a-zA-Z0-9\-]+)*)/',
            function (
                array $matches,
            ): string {
                $directive = strtolower($matches[1]);

                // The regex guarantees index 2 exists, even as an empty string.
                $modifiers = $matches[2];

                // If it's in our map, translate it (e.g., @model -> x-model)
                if (isset(self::MAPPING[$directive])) {
                    return self::MAPPING[$directive] . $modifiers;
                }

                // Otherwise, leave it completely alone (e.g., @click -> @click)
                return '@' . $directive . $modifiers;
            },
            $code,
        ) ?? $code;
    }
}
