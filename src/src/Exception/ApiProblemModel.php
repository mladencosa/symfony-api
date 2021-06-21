<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * An Api exception model should be used for all HTTP exceptions
 */
class ApiProblemModel
{
    private int $statusCode;
    private ?string $message;
    private array $extraData = [];

    public function __construct(int $statusCode, ?string $message = null)
    {
        if (!array_key_exists($statusCode, Response::$statusTexts)) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        $this->statusCode = $statusCode;

        if ($message === null) {
            $message = Response::$statusTexts[$statusCode] ?? 'Unknown status code';
        }

        $this->message = $message;
    }

    public function toArray(): array
    {
        return array_merge(
            $this->extraData,
            array(
                'status' => $this->statusCode,
                'message' => $this->message,
            )
        );
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setExtraData(string $name, $value): void
    {
        $this->extraData[$name] = $value;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
