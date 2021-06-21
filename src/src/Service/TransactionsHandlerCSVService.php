<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\TransactionsHandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class TransactionsHandlerCSVService implements TransactionsHandlerInterface
{
    private const ROW_LIMIT = 500;
    private const CSV_PATH = __DIR__ . '/../../database/csv/transactions.csv';

    /**
     * @return array
     * @throws Exception
     */
    public function getTransactionsList(): array
    {
        $transactions = [];

        $row = 0;
        $keys = [];

        if (($handle = fopen(self::CSV_PATH, 'rb')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if (empty($data)) {
                    throw new Exception("CSV can not be processed", Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                if ($row === 0) {
                    // save array keys
                    $keys = $data;
                }

                $transactionData = [];
                foreach ($data as $key => $value) {
                    $transactionData[$keys[$key]] = $value;
                }

                $transactions[] = $transactionData;

                $row++;
                // Limit rows by default 500
                if ($row > self::ROW_LIMIT) {
                    break;
                }
            }
            fclose($handle);
        }

        return $transactions;
    }
}
