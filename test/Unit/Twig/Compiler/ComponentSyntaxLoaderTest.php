<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Loader\LoaderInterface;
use Twig\Source;
use Vyse\Framework\Twig\Compiler\ComponentSyntaxLoader;
use Vyse\Framework\Twig\Compiler\Pass\ComponentAttributeForwardingPass;
use Vyse\Framework\Twig\Compiler\Pass\ComponentTagConversionPass;
use Vyse\Framework\Twig\Compiler\Pass\NamedSlotDefinitionPass;
use Vyse\Framework\Twig\Compiler\Pass\RootAttributeInjectionPass;
use Vyse\Framework\Twig\Compiler\Pass\SlotOutputResolutionPass;
use Vyse\Framework\Twig\Compiler\Pass\TailwindAttributeMergePass;

class ComponentSyntaxLoaderTest extends TestCase
{
    private MockObject & LoaderInterface $innerLoaderMock;

    protected function setUp(): void
    {
        $this->innerLoaderMock = $this->createMock(LoaderInterface::class);
    }

    public function testItRunsOnlyGlobalPassesForStandardTemplates(): void
    {
        $originalSource = new Source('START', 'home.html.twig', '');
        $this->innerLoaderMock->method('getSourceContext')->willReturn($originalSource);

        $tagPass = $this->createMock(ComponentTagConversionPass::class);
        $slotDefPass = $this->createMock(NamedSlotDefinitionPass::class);
        $rootInjPass = $this->createMock(RootAttributeInjectionPass::class);
        $slotResPass = $this->createMock(SlotOutputResolutionPass::class);
        $tailwindPass = $this->createMock(TailwindAttributeMergePass::class);
        $attrFwdPass = $this->createMock(ComponentAttributeForwardingPass::class);

        $tagPass->expects(self::once())->method('__invoke')->with('START')->willReturn('PASS_1');
        $slotDefPass->expects(self::once())->method('__invoke')->with('PASS_1')->willReturn('PASS_2');

        $rootInjPass->expects(self::never())->method('__invoke');
        $slotResPass->expects(self::never())->method('__invoke');
        $tailwindPass->expects(self::never())->method('__invoke');

        $attrFwdPass->expects(self::once())->method('__invoke')->with('PASS_2')->willReturn('PASS_FINAL');

        $loader = new ComponentSyntaxLoader(
            $this->innerLoaderMock,
            $tagPass,
            $slotDefPass,
            $rootInjPass,
            $slotResPass,
            $tailwindPass,
            $attrFwdPass,
        );

        $source = $loader->getSourceContext('home.html.twig');
        self::assertSame('PASS_FINAL', $source->getCode());
    }

    public function testItRunsAllPassesInOrderForComponentTemplates(): void
    {
        $originalSource = new Source('START', 'components/Button.html.twig', '');
        $this->innerLoaderMock->method('getSourceContext')->willReturn($originalSource);

        $tagPass = $this->createMock(ComponentTagConversionPass::class);
        $slotDefPass = $this->createMock(NamedSlotDefinitionPass::class);
        $rootInjPass = $this->createMock(RootAttributeInjectionPass::class);
        $slotResPass = $this->createMock(SlotOutputResolutionPass::class);
        $tailwindPass = $this->createMock(TailwindAttributeMergePass::class);
        $attrFwdPass = $this->createMock(ComponentAttributeForwardingPass::class);

        $tagPass->expects(self::once())->method('__invoke')->with('START')->willReturn('PASS_1');
        $slotDefPass->expects(self::once())->method('__invoke')->with('PASS_1')->willReturn('PASS_2');
        $rootInjPass->expects(self::once())->method('__invoke')->with('PASS_2')->willReturn('PASS_3');
        $slotResPass->expects(self::once())->method('__invoke')->with('PASS_3')->willReturn('PASS_4');
        $tailwindPass->expects(self::once())->method('__invoke')->with('PASS_4')->willReturn('PASS_5');
        $attrFwdPass->expects(self::once())->method('__invoke')->with('PASS_5')->willReturn('PASS_FINAL');

        $loader = new ComponentSyntaxLoader(
            $this->innerLoaderMock,
            $tagPass,
            $slotDefPass,
            $rootInjPass,
            $slotResPass,
            $tailwindPass,
            $attrFwdPass,
        );

        $source = $loader->getSourceContext('components/Button.html.twig');
        self::assertSame('PASS_FINAL', $source->getCode());
    }
}
