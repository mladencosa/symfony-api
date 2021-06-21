<?php

declare(strict_types=1);

namespace App\Factory;

use App\Contract\TransactionsHandlerInterface;
use App\Exception\ApiProblemException;
use App\Exception\ApiProblemModel;
use App\Service\TransactionsHandlerCSVService;
use App\Service\TransactionsHandlerDBService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class TransactionsHandlerFactory
{
    private const SOURCE_DB = 'db';
    private const SOURCE_CSV = 'csv';
    private const ALLOWED_SOURCES = [self::SOURCE_DB, self::SOURCE_CSV];

    private EntityManagerInterface $entityManager;
    private TransactionsHandlerInterface $transactionsHandler;
    private ?string $source;

    public function __construct(EntityManagerInterface $entityManager, ?string $source)
    {
        $this->entityManager = $entityManager;
        $this->source = $source;

        $this->createTransactionHandler();
    }

    private function createTransactionHandler(): void
    {
        // Validate source
        if (!$this->source) {
            $this->throwAPIException(Response::HTTP_BAD_REQUEST, "Parameter source is missing!");
        }

        if (!in_array($this->source, self::ALLOWED_SOURCES, false)) {
            $errorMsg = sprintf("Allowed sources are %s", implode(",", self::ALLOWED_SOURCES));
            $this->throwAPIException(Response::HTTP_BAD_REQUEST, $errorMsg);
        }

        switch ($this->source) {
            case "db":
                $handler = new TransactionsHandlerDBService($this->entityManager);
                break;
            case "csv":
                $handler = new TransactionsHandlerCSVService();
                break;
            default:
                // use it as a default, but this will be never reached due to source check
                $handler = new TransactionsHandlerDBService($this->entityManager);
                break;
        }

        $this->transactionsHandler = $handler;
    }

    public function getTransactionHandler(): TransactionsHandlerInterface
    {
        return $this->transactionsHandler;
    }

    private function throwAPIException(int $code, string $message): void
    {
        $apiProblem = new ApiProblemModel($code, $message);
        throw new ApiProblemException($apiProblem);
    }
}
