<?php

namespace App\Service\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

interface JsonResponseServiceInterface
{
    public function success(mixed $data, string $message = 'Success', int $statusCode = 200): JsonResponse;
    public function error(string $message, int $statusCode = 400, mixed $data = null): JsonResponse;
    public function validationError(array $errors, string $message = 'Validation failed'): JsonResponse;
}
