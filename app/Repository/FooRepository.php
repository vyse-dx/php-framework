<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Foo;
use App\EntityRepository\FooEntityRepository;

final class FooRepository
{
    public function __construct(
        private FooEntityRepository $foos,
    ) {
    }

    public function countAll(): int
    {
        return $this->foos->count();
    }

    public function save(
        Foo $foo,
    ): void {
        $this->foos->persist($foo);
        $this->foos->flush();
    }

    public function delete(
        Foo $foo,
    ): void {
        $this->foos->remove($foo);
        $this->foos->flush();
    }

    public function findById(
        int $id,
    ): ?Foo {
        return $this->foos->find($id);
    }
}
