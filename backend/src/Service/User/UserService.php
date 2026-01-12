<?php

namespace App\Service\User;

use App\Dto\User\Response\UserResponseDto;
use App\Entity\User;
use App\Repository\UserRepository;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserResponseDto(User $user): UserResponseDto
    {
        return new UserResponseDto(
            $user->getId(),
            $user->getName(),
            $user->getLogin(),
            $user->getRole()
        );
    }

    public function getUserByLogin(string $login): ?User
    {
        return $this->userRepository->findByLogin($login);
    }
}
