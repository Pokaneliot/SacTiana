<?php

namespace App\Service\User;

use App\Dto\User\Response\UserResponseDto;
use App\Entity\User;

interface UserServiceInterface
{
    public function getUserResponseDto(User $user): UserResponseDto;
    public function getUserByLogin(string $login): ?User;
}
