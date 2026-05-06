<?php

declare(strict_types=1);

namespace Vyse\Framework\Twig\Compiler;

use Twig\Loader\LoaderInterface;
use Twig\Source;
use Vyse\Framework\Twig\Compiler\Pass\ComponentAttributeForwardingPass;
use Vyse\Framework\Twig\Compiler\Pass\ComponentTagConversionPass;
use Vyse\Framework\Twig\Compiler\Pass\NamedSlotDefinitionPass;
use Vyse\Framework\Twig\Compiler\Pass\RootAttributeInjectionPass;
use Vyse\Framework\Twig\Compiler\Pass\SlotOutputResolutionPass;
use Vyse\Framework\Twig\Compiler\Pass\TailwindAttributeMergePass;

class ComponentSyntaxLoader implements LoaderInterface
{
    public function __construct(
        private LoaderInterface $innerLoader,
        private ComponentTagConversionPass $tagConversionPass = new ComponentTagConversionPass(),
        private NamedSlotDefinitionPass $slotDefinitionPass = new NamedSlotDefinitionPass(),
        private RootAttributeInjectionPass $rootInjectionPass = new RootAttributeInjectionPass(),
        private SlotOutputResolutionPass $slotResolutionPass = new SlotOutputResolutionPass(),
        private TailwindAttributeMergePass $tailwindMergePass = new TailwindAttributeMergePass(),
        private ComponentAttributeForwardingPass $attributeForwardingPass = new ComponentAttributeForwardingPass(),
    ) {
    }

    public function getSourceContext(
        string $name,
    ): Source {
        $source = $this->innerLoader->getSourceContext($name);
        $code = $source->getCode();
        $isComponent = str_starts_with($name, 'components/') || str_contains($name, '/components/');

        $code = ($this->tagConversionPass)($code);
        $code = ($this->slotDefinitionPass)($code);

        if ($isComponent) {
            $code = ($this->rootInjectionPass)($code);
            $code = ($this->slotResolutionPass)($code);
            $code = ($this->tailwindMergePass)($code);
        }

        $code = ($this->attributeForwardingPass)($code);

        return new Source($code, $source->getName(), $source->getPath());
    }

    public function getCacheKey(
        string $name,
    ): string {
        return $this->innerLoader->getCacheKey($name);
    }

    public function isFresh(
        string $name,
        int $time,
    ): bool {
        return $this->innerLoader->isFresh($name, $time);
    }

    public function exists(
        string $name,
    ): bool {
        return $this->innerLoader->exists($name);
    }
}
