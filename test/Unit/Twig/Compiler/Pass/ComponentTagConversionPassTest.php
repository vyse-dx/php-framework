<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler\Pass;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Twig\Compiler\Pass\ComponentTagConversionPass;

class ComponentTagConversionPassTest extends TestCase
{
    private ComponentTagConversionPass $pass;

    protected function setUp(): void
    {
        $this->pass = new ComponentTagConversionPass;
    }

    public function testItConvertsStandardOpeningAndClosingTags(): void
    {
        $source = '<Submit>Click</Submit>';
        $expected = '<twig:Submit>Click</twig:Submit>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItConvertsSelfClosingTags(): void
    {
        $source = '<Alert type="error" />';
        $expected = '<twig:Alert type="error" />';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItPreservesAttributesDuringConversion(): void
    {
        $source = '<Card id="main" class="p-4" data-test="true">Content</Card>';
        $expected = '<twig:Card id="main" class="p-4" data-test="true">Content</twig:Card>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItIgnoresStandardHtmlTags(): void
    {
        $source = '<div class="test"><span>Bar</span></div>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItIgnoresLowercaseCustomTags(): void
    {
        $source = '<my-component>Content</my-component>';

        self::assertSame($source, ($this->pass)($source));
    }
}
