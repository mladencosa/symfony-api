<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixture extends Fixture
{
    abstract protected function loadData(ObjectManager $manager): void;

    public function load(ObjectManager $manager): void
    {
        $this->loadData($manager);
    }
}
