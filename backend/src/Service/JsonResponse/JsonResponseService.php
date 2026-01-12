<?php

namespace App\Service\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseService implements JsonResponseServiceInterface
{
    public function success(mixed $data, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null
        ], $statusCode);
    }

    public function error(string $message, int $statusCode = 400, mixed $data = null): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'errors' => null
        ], $statusCode);
    }

    public function validationError(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors
        ], 422);
    }
}
