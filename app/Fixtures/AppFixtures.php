<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\Foo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(
        ObjectManager $manager,
    ): void {
        for ($i = 1; $i <= 5; $i++) {
            $foo = new Foo("Test Item $i");
            $manager->persist($foo);
        }

        $manager->flush();
    }
}
