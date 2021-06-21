<?php

declare(strict_types=1);

namespace App\Validator;

use App\Exception\ApiProblemException;
use App\Exception\ApiProblemModel;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDetailsRequestValidator
{
    private const ALLOWED_PARAMETERS = ["firstName", "lastName", "phoneNumber", "citizenshipIso"];

    public static function validate(Request $request): void
    {
        if (!$request instanceof Request) {
            static::throwApiException(Response::HTTP_INTERNAL_SERVER_ERROR, "Internal problem");
        }

        $data = '';

        try {
            $data = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            static::throwApiException($e->getCode(), $e->getMessage());
        }

        if (empty($data)) {
            static::throwApiException(Response::HTTP_BAD_REQUEST, "Request can be empty!");
        }

        foreach (array_keys($data) as $param) {
            if (!in_array($param, self::ALLOWED_PARAMETERS, false)) {
                static::throwApiException(Response::HTTP_BAD_REQUEST, "Parameter $param does not exist");
            }
        }

        $request->request->replace($data);
    }

    private static function throwApiException(int $status, string $message = ''): void
    {
        $apiProblem = new ApiProblemModel($status, $message);
        throw new ApiProblemException($apiProblem);
    }
}
