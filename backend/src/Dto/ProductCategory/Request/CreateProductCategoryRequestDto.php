<?php

namespace App\Dto\ProductCategory\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductCategoryRequestDto
{
    #[Assert\NotBlank(message: 'Category name is required')]
    #[Assert\Length(max: 50, maxMessage: 'Category name cannot be longer than {{ limit }} characters')]
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
