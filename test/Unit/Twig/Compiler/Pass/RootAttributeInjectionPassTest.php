<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler\Pass;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Twig\Compiler\Pass\RootAttributeInjectionPass;

class RootAttributeInjectionPassTest extends TestCase
{
    private RootAttributeInjectionPass $pass;

    protected function setUp(): void
    {
        $this->pass = new RootAttributeInjectionPass;
    }

    public function testItInjectsAttributesIntoStandardRootTag(): void
    {
        $source = '<div class="test"><span>Bar</span></div>';
        $expected = '<div class="test" {{ attributes }}><span>Bar</span></div>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItInjectsAttributesIntoSelfClosingRootTag(): void
    {
        $source = '<input type="text" />';
        $expected = '<input type="text" {{ attributes }} />';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItOnlyInjectsIntoTheAbsoluteFirstTag(): void
    {
        $source = '<div><Submit>Send</Submit></div>';
        $expected = '<div {{ attributes }}><Submit>Send</Submit></div>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItAbortsInjectionIfFirstTagIsASlot(): void
    {
        $source = '<slot:header><h1>Title</h1></slot:header>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItAbortsInjectionIfFirstTagIsATwigBlock(): void
    {
        $source = '<twig:block name="header"><h1>Title</h1></twig:block>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItDoesNotInjectIfAttributesAreAlreadyPresent(): void
    {
        $source = '<div class="bg-red-500" {{ attributes }}><span>Content</span></div>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItDoesNotHoldStateBetweenInvocations(): void
    {
        $source1 = '<div>First</div>';
        $source2 = '<span>Second</span>';

        self::assertSame('<div {{ attributes }}>First</div>', ($this->pass)($source1));
        self::assertSame('<span {{ attributes }}>Second</span>', ($this->pass)($source2));
    }
}
