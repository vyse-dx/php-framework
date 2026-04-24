<?php

declare(strict_types=1);

namespace Vyse\Framework\Doctrine\EntityRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T of object
 * @template-extends ServiceEntityRepository<T>
 */
abstract class AbstractEntityRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, $this->getFqcn());
    }

    /**
     * Queues an entity to be saved to the database.
     */
    public function persist(
        object $entity,
    ): void {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * Queues an entity to be deleted from the database.
     */
    public function remove(
        object $entity,
    ): void {
        $this->getEntityManager()->remove($entity);
    }

    /**
     * Executes all queued database operations (commits the transaction).
     */
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return class-string<T>
     */
    abstract protected function getFqcn(): string;
}
