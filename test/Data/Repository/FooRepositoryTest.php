<?php

declare(strict_types=1);

namespace App\Tests\Data\Repository;

use App\Entity\Foo;
use App\Repository\FooRepository;
use Vyse\Framework\PhpUnit\TestCase\DataTestCase;

final class FooRepositoryTest extends DataTestCase
{
    private FooRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->getService(FooRepository::class);
    }

    public function testSaveAndFindByIdRetrievesCorrectEntity(): void
    {
        $foo = new Foo('Test Entity');
        $this->repository->save($foo);

        $this->clearEntityManager();

        $found = $this->repository->findById($foo->getId());

        self::assertNotNull($found);
        self::assertSame('Test Entity', $found->getName());
    }
}
