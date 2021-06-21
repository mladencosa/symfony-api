<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Generator;

class UserFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $idx = 1;
        foreach ($this->getUsers() as $row) {
            [$email, $active] = $row;

            $user = new User();
            $user->setEmail($email);
            $user->setActive($active);
            // store for later usage in UserDetailsFixtures
            $this->addReference(User::class . '_' . $email, $user);
            // store for later usage in TransactionFixtures
            $this->addReference(User::class . '_' . $idx, $user);

            $manager->persist($user);

            $idx++;
        }
        $manager->flush();
    }

    private function getUsers(): Generator
    {
        // [$email, $active]
        yield ['alex@tempmail.com', true];
        yield ['maria@tempmail.com', true];
        yield ['john@tempmail.com', true];
        yield ['dominik@test.com', true];
        yield ['andreas@yahoo.de', true];
        yield ['Taaaaaaa@test.com', false];
        yield ['rerere@test_mail.com', false];
    }
}
