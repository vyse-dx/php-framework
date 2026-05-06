<?php

declare(strict_types=1);

namespace Test\Integration\Twig\Compiler;

use PHPUnit\Framework\MockObject\MockObject;
use Twig\Loader\LoaderInterface;
use Twig\Source;
use Vyse\Framework\Twig\Compiler\ComponentSyntaxLoader;
use Vyse\Toolchain\PhpUnit\TestCase\IntegrationTestCase;

class ComponentSyntaxLoaderTest extends IntegrationTestCase
{
    private MockObject & LoaderInterface $innerLoaderMock;
    private ComponentSyntaxLoader $loader;

    protected function setUp(): void
    {
        $this->innerLoaderMock = $this->createMock(LoaderInterface::class);

        // Uses the base class to auto-wire the real default pipeline,
        // while safely mocking out the underlying Twig loader dependency.
        $this->loader = $this->make(ComponentSyntaxLoader::class, [
            LoaderInterface::class => $this->innerLoaderMock,
        ]);
    }

    public function testItDelegatesExistsToInnerLoader(): void
    {
        $this->innerLoaderMock->expects(self::once())
            ->method('exists')
            ->with('components/test.html.twig')
            ->willReturn(true)
        ;

        self::assertTrue($this->loader->exists('components/test.html.twig'));
    }

    public function testItDelegatesGetCacheKeyToInnerLoader(): void
    {
        $this->innerLoaderMock->expects(self::once())
            ->method('getCacheKey')
            ->with('components/test.html.twig')
            ->willReturn('cache_key_123')
        ;

        self::assertSame('cache_key_123', $this->loader->getCacheKey('components/test.html.twig'));
    }

    public function testItDelegatesIsFreshToInnerLoader(): void
    {
        $this->innerLoaderMock->expects(self::once())
            ->method('isFresh')
            ->with('components/test.html.twig', 1000)
            ->willReturn(false)
        ;

        self::assertFalse($this->loader->isFresh('components/test.html.twig', 1000));
    }

    public function testItOnlyAppliesGlobalPassesToStandardTemplates(): void
    {
        // 1. <Submit> should become <twig:Submit> (ComponentTagConversionPass - Global)
        // 2. <slot:header> should become {% block header %} (NamedSlotDefinitionPass - Global)
        // 3. class="flex" {{ attributes }} should REMAIN UNTOUCHED (TailwindAttributeMergePass - Component Only)
        $rawHtml = '<slot:header>Title</slot:header><div class="flex" {{ attributes }}><Submit>Send</Submit></div>';
        $originalSource = new Source($rawHtml, 'pages/home.html.twig', '');

        $this->innerLoaderMock->method('getSourceContext')->willReturn($originalSource);

        $source = $this->loader->getSourceContext('pages/home.html.twig');

        // Notice how the <div> attributes are not merged, proving component passes were skipped
        $expected = '{% block header %}Title{% endblock %}<div class="flex" {{ attributes }}><twig:Submit>Send</twig:Submit></div>';

        self::assertSame($expected, $source->getCode());
    }

    public function testItAppliesAllPassesForComponentTemplates(): void
    {
        // All passes run, including component-specific ones like TailwindAttributeMergePass
        $rawHtml = '<slot:header>Title</slot:header><div class="flex" {{ attributes }}><Submit>Send</Submit></div>';
        $originalSource = new Source($rawHtml, 'components/test.html.twig', '');

        $this->innerLoaderMock->method('getSourceContext')->willReturn($originalSource);

        $source = $this->loader->getSourceContext('components/test.html.twig');

        // Notice how `class="flex" {{ attributes }}` is fully transformed into Tailwind merge logic
        $expected = '{% block header %}Title{% endblock %}<div class="{{ (\'flex\' ~ \' \' ~ attributes.render(\'class\'))|tailwind_merge|trim }}" {{ attributes }}><twig:Submit>Send</twig:Submit></div>';

        self::assertSame($expected, $source->getCode());
    }
}
