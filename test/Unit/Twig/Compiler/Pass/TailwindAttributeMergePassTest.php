<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler\Pass;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Twig\Compiler\Pass\TailwindAttributeMergePass;

class TailwindAttributeMergePassTest extends TestCase
{
    private TailwindAttributeMergePass $pass;

    protected function setUp(): void
    {
        $this->pass = new TailwindAttributeMergePass;
    }

    public function testItMergesStaticStringClasses(): void
    {
        $source = '<div class="flex items-center" {{ attributes }}>Content</div>';
        $expected = '<div class="{{ (\'flex items-center\' ~ \' \' ~ attributes.render(\'class\'))|tailwind_merge|trim }}" {{ attributes }}>Content</div>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItMergesDynamicTwigVariableClasses(): void
    {
        $source = '<div class="{{ customClass }}" {{ attributes }}>Content</div>';
        $expected = '<div class="{{ (customClass ~ \' \' ~ attributes.render(\'class\'))|tailwind_merge|trim }}" {{ attributes }}>Content</div>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItEscapesSingleQuotesInStaticClasses(): void
    {
        $source = '<div class="before:content-[\'\'] bg-red-500" {{ attributes }}></div>';
        $expected = '<div class="{{ (\'before:content-[\\\'\\\'] bg-red-500\' ~ \' \' ~ attributes.render(\'class\'))|tailwind_merge|trim }}" {{ attributes }}></div>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItIgnoresClassesWithoutAttributesNextToThem(): void
    {
        $source = '<div class="static-class"><span>Bar</span></div>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItHandlesVaryingWhitespace(): void
    {
        $source = '<div class="p-4"   {{ attributes }}></div>';
        $expected = '<div class="{{ (\'p-4\' ~ \' \' ~ attributes.render(\'class\'))|tailwind_merge|trim }}" {{ attributes }}></div>';

        self::assertSame($expected, ($this->pass)($source));
    }
}
