<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class ApiProblemException extends HttpException
{
    private ApiProblemModel $apiProblemModel;

    public function __construct(
        ApiProblemModel $apiProblemModel,
        Exception $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        $this->apiProblemModel = $apiProblemModel;
        $statusCode = $apiProblemModel->getStatusCode();
        $message = $apiProblemModel->getMessage();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getApiProblem(): ApiProblemModel
    {
        return $this->apiProblemModel;
    }
}
