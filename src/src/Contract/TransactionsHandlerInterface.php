<?php

declare(strict_types=1);

namespace App\Contract;

interface TransactionsHandlerInterface
{
    public function getTransactionsList(): array;
}
