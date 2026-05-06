<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler\Pass;

class NamedSlotDefinitionPass
{
    /**
     * Converts declarative <slot:name> tags into native Twig {% block name %} tags.
     */
    public function __invoke(
        string $code,
    ): string {
        return preg_replace('/<slot:([a-zA-Z0-9_]+)>(.*?)<\/slot:\1>/s', '{% block $1 %}$2{% endblock %}', $code) ?? $code;
    }
}
