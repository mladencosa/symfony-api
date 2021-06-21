<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TransactionFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $code = $this->generateRandomCode();
            $amount = (string)rand(100, 5000) . '.' . rand(1, 100);
            $userId = rand(1, 6);

            /** @var User $user */
            $user = $this->getReference(User::class . '_' . $userId);

            $transaction = new Transaction();
            $transaction->setUser($user);
            $transaction->setAmount($amount);
            $transaction->setCode($code);

            $manager->persist($transaction);
        }
        $manager->flush();
    }

    private function generateRandomCode(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
