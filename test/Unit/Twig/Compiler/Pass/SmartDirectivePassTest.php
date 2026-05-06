<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler\Pass;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Twig\Compiler\Pass\SmartDirectivePass;

class SmartDirectivePassTest extends TestCase
{
    private SmartDirectivePass $pass;

    protected function setUp(): void
    {
        $this->pass = new SmartDirectivePass;
    }

    public function testItConvertsAlpineShorthands(): void
    {
        $source = '<div @model="query" @show="isOpen" @ref="searchInput"></div>';
        $expected = '<div x-model="query" x-show="isOpen" x-ref="searchInput"></div>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItConvertsHtmxShorthands(): void
    {
        $source = '<button @get="/api/search" @target="#results" @push-url="true">Search</button>';
        $expected = '<button hx-get="/api/search" hx-target="#results" hx-push-url="true">Search</button>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItPreservesModifiersOnConvertedDirectives(): void
    {
        $source = '<input @model.debounce.500ms="query" @transition.opacity.duration.200ms>';
        $expected = '<input x-model.debounce.500ms="query" x-transition.opacity.duration.200ms>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItIgnoresNativeAlpineEvents(): void
    {
        // "click" and "keydown" are not in the mapping dictionary, so they should remain untouched.
        $source = '<button @click="isOpen = true" @keydown.escape.window="isOpen = false">Open</button>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItIgnoresEmailAddressesAndInlineMentions(): void
    {
        // The regex requires a space or < before the @, and checks the dictionary.
        // Even with a space (like " @admin"), "admin" isn't in the mapping.
        $source = '<a href="mailto:admin@example.com">Contact @admin</a>';

        self::assertSame($source, ($this->pass)($source));
    }

    public function testItHandlesMultipleMixedDirectivesOnOneElement(): void
    {
        $source = '<input @model="query" @focus="open = true" @get="/search" @target="#res" class="p-2">';
        $expected = '<input x-model="query" @focus="open = true" hx-get="/search" hx-target="#res" class="p-2">';

        self::assertSame($expected, ($this->pass)($source));
    }
}
