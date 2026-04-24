<?php

declare(strict_types=1);

namespace Test\Unit\Entity;

use App\Entity\Foo;
use Error;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{
    private Foo $foo;

    protected function setUp(): void
    {
        $this->foo = new Foo('Test Name');
    }

    public function testInitialState(): void
    {
        self::assertSame('Test Name', $this->foo->getName());
    }

    public function testNameCanBeUpdated(): void
    {
        $this->foo->setName('Updated');

        self::assertSame('Updated', $this->foo->getName());
    }

    public function testAccessingIdBeforePersistenceThrowsError(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Typed property App\Entity\Foo::$id must not be accessed before initialization');

        $this->foo->getId();
    }
}
