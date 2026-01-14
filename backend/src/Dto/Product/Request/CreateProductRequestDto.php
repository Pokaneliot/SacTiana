<?php

namespace App\Dto\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductRequestDto
{
    #[Assert\NotBlank(message: 'Reference is required')]
    #[Assert\Length(max: 50, maxMessage: 'Reference cannot be longer than {{ limit }} characters')]
    private ?string $ref = null;

    #[Assert\Length(max: 50, maxMessage: 'Name cannot be longer than {{ limit }} characters')]
    private ?string $name = null;

    #[Assert\NotBlank(message: 'Purchase price is required')]
    #[Assert\Positive(message: 'Purchase price must be positive')]
    private ?float $purchasePrice = null;

    #[Assert\NotBlank(message: 'Selling price is required')]
    #[Assert\Positive(message: 'Selling price must be positive')]
    private ?float $sellingPrice = null;

    #[Assert\NotBlank(message: 'Category ID is required')]
    #[Assert\Positive(message: 'Category ID must be positive')]
    private ?int $categoryId = null;

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPurchasePrice(): ?float
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(?float $purchasePrice): self
    {
        $this->purchasePrice = $purchasePrice;
        return $this;
    }

    public function getSellingPrice(): ?float
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(?float $sellingPrice): self
    {
        $this->sellingPrice = $sellingPrice;
        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;
        return $this;
    }
}
