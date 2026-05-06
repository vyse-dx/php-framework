<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler\Pass;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Twig\Compiler\Pass\SlotOutputResolutionPass;

class SlotOutputResolutionPassTest extends TestCase
{
    private SlotOutputResolutionPass $pass;

    protected function setUp(): void
    {
        $this->pass = new SlotOutputResolutionPass;
    }

    public function testItConvertsStandardNamedSlot(): void
    {
        $source = '<div>{{ slots.header }}</div>';
        $expected = '<div>{% block header %}{{ outerBlocks is defined and outerBlocks.header != "outer__block_fallback" ? block(outerBlocks.header) : "" }}{% endblock %}</div>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItConvertsDefaultSlotToContent(): void
    {
        $source = '<main>{{ slots.default }}</main>';
        $expected = '<main>{% block content %}{{ outerBlocks is defined and outerBlocks.content != "outer__block_fallback" ? block(outerBlocks.content) : "" }}{% endblock %}</main>';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItHandlesVaryingWhitespace(): void
    {
        $source = '<footer>{{slots.footer}} - {{  slots.aside  }}</footer>';
        $expectedFooter = '{% block footer %}{{ outerBlocks is defined and outerBlocks.footer != "outer__block_fallback" ? block(outerBlocks.footer) : "" }}{% endblock %}';
        $expectedAside = '{% block aside %}{{ outerBlocks is defined and outerBlocks.aside != "outer__block_fallback" ? block(outerBlocks.aside) : "" }}{% endblock %}';

        $expected = sprintf('<footer>%s - %s</footer>', $expectedFooter, $expectedAside);

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItIgnoresStandardVariables(): void
    {
        $source = '<div>{{ user.name }}</div>';

        self::assertSame($source, ($this->pass)($source));
    }
}
