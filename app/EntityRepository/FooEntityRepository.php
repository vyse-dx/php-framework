<?php

declare(strict_types=1);

namespace App\EntityRepository;

use App\Entity\Foo;
use Vyse\Framework\Doctrine\EntityRepository\AbstractEntityRepository;

/**
 * @template-extends AbstractEntityRepository<Foo>
 */
final class FooEntityRepository extends AbstractEntityRepository
{
    /**
     * @return class-string<Foo>
     */
    protected function getFqcn(): string
    {
        return Foo::class;
    }
}
