<?php

namespace App\Service\Validation;

use App\Dto\Common\ErrorResponseDto;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService implements ValidationServiceInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(object $dto): array
    {
        $violations = $this->validator->validate($dto);
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = new ErrorResponseDto(
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
        }

        return $errors;
    }

    public function hasErrors(object $dto): bool
    {
        return count($this->validator->validate($dto)) > 0;
    }

    public function getErrorsArray(object $dto): array
    {
        $errors = $this->validate($dto);
        return array_map(fn($error) => $error->toArray(), $errors);
    }
}
