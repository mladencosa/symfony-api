<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\User;
use App\Entity\UserDetails;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Generator;

class UserDetailsFixtures extends BaseFixture implements DependentFixtureInterface
{

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    protected function loadData(ObjectManager $manager): void
    {
        foreach ($this->getUsers() as $key => $row) {
            [$email, $firstname, $lastname, $phoneNumber] = $row;

            if (!$this->hasReference(User::class . '_' . $email)) {
                continue;
            }
            /** @var User $user */
            $user = $this->getReference(User::class . '_' . $email);

            // we want to ensure that we add Austrian citizenship for at least first 3 users
            // Austria will be always on the index 0
            if ($key <= 2) {
                /** @var Country $country */
                $country = $this->getReference(Country::class . '_' . 0);
            } else {
                /** @var Country $country */
                $country = $this->getReference(Country::class . '_' . random_int(1, 5));
            }

            $userDetails = new UserDetails();
            $userDetails->setFirstName($firstname);
            $userDetails->setLastName($lastname);
            $userDetails->setPhoneNumber($phoneNumber);
            $userDetails->setCountry($country);
            $userDetails->setUser($user);


            $manager->persist($userDetails);
        }
        $manager->flush();
    }

    private function getUsers(): Generator
    {
        // [$email, $firstname, $lastname, $phoneNumber]
        yield ['alex@tempmail.com', 'Alex', 'Petro', '0043664111111'];
        yield ['dominik@test.com', 'Dominik', 'Allan', '00436644444444'];
        yield ['andreas@yahoo.de', 'Andreas', 'Snow', '004366455555555'];
        yield ['rerere@test_mail.com', 'Igor', 'Snow', '0043664777777'];
        yield ['Taaaaaaa@test.com', 'Max', 'Mustermann', '00436646666666'];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CountryFixtures::class
        ];
    }
}
