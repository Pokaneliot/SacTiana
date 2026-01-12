<?php

namespace App\Dto\User\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequestDto
{
    #[Assert\NotBlank(message: 'Login is required')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Login must be at least {{ limit }} characters',
        maxMessage: 'Login cannot be longer than {{ limit }} characters'
    )]
    private string $login;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(
        min: 6,
        minMessage: 'Password must be at least {{ limit }} characters'
    )]
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
