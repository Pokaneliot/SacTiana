<?php

namespace App\Service\Validation;

interface ValidationServiceInterface
{
    public function validate(object $dto): array;
    public function hasErrors(object $dto): bool;
}
