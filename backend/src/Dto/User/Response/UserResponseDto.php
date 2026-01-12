<?php

namespace App\Dto\User\Response;

use Symfony\Component\Validator\Constraints as Assert;

class UserResponseDto
{
    #[Assert\Positive]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $login;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['ROLE_ADMIN', 'ROLE_USER'])]
    private string $role;

    public function __construct(int $id, string $name, string $login, string $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->login = $login;
        $this->role = $role;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'login' => $this->login,
            'role' => $this->role,
        ];
    }
}
