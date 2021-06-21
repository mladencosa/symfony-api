<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\TransactionsHandlerInterface;
use App\Factory\TransactionsHandlerFactory;
use Doctrine\ORM\EntityManagerInterface;

class TransactionsService
{
    private EntityManagerInterface $repository;

    public function __construct(EntityManagerInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getList(?string $source): array
    {
        $handlerFactory = new TransactionsHandlerFactory($this->repository, $source);
        return $this->generateList($handlerFactory->getTransactionHandler());
    }

    private function generateList(TransactionsHandlerInterface $handler): array
    {
        return $handler->getTransactionsList();
    }
}
