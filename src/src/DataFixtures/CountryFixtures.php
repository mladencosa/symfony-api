<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Persistence\ObjectManager;
use Generator;

class CountryFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $id = 0;
        foreach ($this->getCountries() as $row) {
            [$name, $iso2, $iso3] = $row;

            $country = new Country();
            $country->setName($name);
            $country->setIso2($iso2);
            $country->setIso3($iso3);

            // store for later usage in UserDetailsFixtures
            $this->addReference(Country::class . '_' . $id, $country);
            $manager->persist($country);
            $id++;
        }
        $manager->flush();
    }

    private function getCountries(): Generator
    {
        // [$name, $iso2, $iso3]
        yield ['Austria','AT','AUT'];
        yield ['France','FR','FRA'];
        yield ['Germany','DE','DEU'];
        yield ['Spain','ES','ESP'];
        yield ['Russian Federation','RU','RUS'];
        yield ['China','CN','CHN'];
    }
}
