<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\TransactionsHandlerInterface;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class TransactionsHandlerDBService implements TransactionsHandlerInterface
{
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Transaction::class);
    }

    public function getTransactionsList(): array
    {
        return $this->repository->findAll();
    }
}
