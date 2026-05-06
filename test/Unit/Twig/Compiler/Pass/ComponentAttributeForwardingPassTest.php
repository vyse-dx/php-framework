<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler\Pass;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Twig\Compiler\Pass\ComponentAttributeForwardingPass;

class ComponentAttributeForwardingPassTest extends TestCase
{
    private ComponentAttributeForwardingPass $pass;

    protected function setUp(): void
    {
        $this->pass = new ComponentAttributeForwardingPass;
    }

    public function testItForwardsAttributesOnStandardComponentTags(): void
    {
        $source = '<twig:Submit {{ attributes }}>Click</twig:Submit>';
        $expected = '<twig:Submit :attributes="attributes">Click</twig:Submit>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItForwardsAttributesOnSelfClosingComponentTags(): void
    {
        $source = '<twig:Alert type="error" {{ attributes }} />';
        $expected = '<twig:Alert type="error" :attributes="attributes" />';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItHandlesVaryingWhitespace(): void
    {
        $source = '<twig:Card id="main"   {{  attributes  }}  >Content</twig:Card>';
        $expected = '<twig:Card id="main"   :attributes="attributes"  >Content</twig:Card>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItIgnoresStandardHtmlTags(): void
    {
        $source = '<div class="test" {{ attributes }}><span>Bar</span></div>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItIgnoresLowercaseTwigTags(): void
    {
        $source = '<twig:block name="header" {{ attributes }}>Title</twig:block>';

        self::assertSame($source, ($this->pass)($source));
    }
}
