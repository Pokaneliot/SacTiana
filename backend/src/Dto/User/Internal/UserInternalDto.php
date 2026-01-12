<?php

namespace App\Dto\User\Internal;

use App\Entity\User;

class UserInternalDto
{
    private ?int $id;
    private string $name;
    private string $login;
    private string $password;
    private string $role;
    private \DateTimeInterface $createdAt;

    public function __construct(
        ?int $id,
        string $name,
        string $login,
        string $password,
        string $role,
        \DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getName(),
            $user->getLogin(),
            $user->getPassword(),
            $user->getRole(),
            $user->getCreatedAt()
        );
    }

    public function toEntity(): User
    {
        $user = new User();
        $user->setName($this->name);
        $user->setLogin($this->login);
        $user->setPassword($this->password);
        $user->setRole($this->role);
        $user->setCreatedAt($this->createdAt);
        
        return $user;
    }

    public function getId(): ?int
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
