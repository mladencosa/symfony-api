<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiProblemException;
use App\Exception\ApiProblemModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;

abstract class AbstractController extends BaseController
{

    protected function throwApiException(int $status, string $message = '', array $errors = []): void
    {
        $apiProblem = new ApiProblemModel($status, $message);
        if (!empty($errors)) {
            $apiProblem->setExtraData('errors', $errors);
        }
        throw new ApiProblemException($apiProblem);
    }
}
