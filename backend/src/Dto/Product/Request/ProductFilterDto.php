<?php

namespace App\Dto\Product\Request;

class ProductFilterDto
{
    private ?string $ref = null;
    private ?float $sellingPrice = null;
    private ?string $createdAt = null;
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

    public function getSellingPrice(): ?float
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(?float $sellingPrice): self
    {
        $this->sellingPrice = $sellingPrice;
        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;
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

    public function toArray(): array
    {
        $filters = [];
        
        if ($this->ref !== null) {
            $filters['ref'] = $this->ref;
        }
        
        if ($this->sellingPrice !== null) {
            $filters['sellingPrice'] = $this->sellingPrice;
        }
        
        if ($this->createdAt !== null) {
            $filters['createdAt'] = new \DateTime($this->createdAt);
        }
        
        if ($this->categoryId !== null) {
            $filters['categoryId'] = $this->categoryId;
        }
        
        return $filters;
    }
}
