<?php

declare(strict_types=1);

namespace Test\Unit\Twig\Compiler\Pass;

use PHPUnit\Framework\TestCase;
use Vyse\Framework\Twig\Compiler\Pass\NamedSlotDefinitionPass;

class NamedSlotDefinitionPassTest extends TestCase
{
    private NamedSlotDefinitionPass $pass;

    protected function setUp(): void
    {
        $this->pass = new NamedSlotDefinitionPass;
    }

    public function testItConvertsNamedSlotsToBlocks(): void
    {
        $source = '<slot:header><h1>Title</h1></slot:header>';
        $expected = '{% block header %}<h1>Title</h1>{% endblock %}';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItHandlesMultilineContentWithinSlots(): void
    {
        $source = "<slot:footer>\n    <div>\n        <p>Copyright</p>\n    </div>\n</slot:footer>";
        $expected = "{% block footer %}\n    <div>\n        <p>Copyright</p>\n    </div>\n{% endblock %}";

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItConvertsMultipleSlotsInOneTemplate(): void
    {
        $source = '<slot:header>Header</slot:header><main>Content</main><slot:footer>Footer</slot:footer>';
        $expected = '{% block header %}Header{% endblock %}<main>Content</main>{% block footer %}Footer{% endblock %}';

        self::assertSame($expected, ($this->pass)($source));
    }

    public function testItIgnoresUnrelatedHtml(): void
    {
        $source = '<div><slot:header>Title</slot:header></div>';
        $expected = '<div>{% block header %}Title{% endblock %}</div>';

        self::assertSame($expected, ($this->pass)($source));
    }
}
