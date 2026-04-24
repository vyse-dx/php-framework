<?php

declare(strict_types=1);

namespace Vyse\Framework\PhpUnit\TestCase;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class DataTestCase extends KernelTestCase
{
    protected EntityManagerInterface $em;

    /**
     * NO-OP SHIELD: Prevents child classes from accidentally destroying the booted
     * kernel if they mistakenly call parent::setUp() out of habit.
     * All true setup logic is handled by #[Before] initializeDatabaseState().
     */
    protected function setUp(): void {}

    #[Before]
    protected function initializeDatabaseState(): void
    {
        parent::setUp();

        self::bootKernel();

        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $this->em = $em;
    }

    #[After]
    protected function closeDatabaseState(): void
    {
        if (isset($this->em)) {
            $this->em->close();
        }
    }

    /**
     * Clears the EntityManager.
     * Call this after saving entities in your 'Arrange' phase.
     */
    protected function clearEntityManager(): void
    {
        $this->em->clear();
    }

    /**
     * Helper to quickly grab application repositories (or any service) from the test container.
     *
     * @template T
     * @param class-string<T> $serviceId
     * @return T
     */
    protected function getService(
        string $serviceId,
    ): mixed {
        $service = static::getContainer()->get($serviceId);
        assert($service instanceof $serviceId);

        return $service;
    }
}
