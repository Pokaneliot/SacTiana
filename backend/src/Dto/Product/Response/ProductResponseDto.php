<?php

namespace App\Dto\Product\Response;

class ProductResponseDto
{
    private int $id;
    private string $ref;
    private ?string $name;
    private float $purchasePrice;
    private float $sellingPrice;
    private string $createdAt;
    private ?int $quantity;
    private CategoryDto $category;
    private ?string $lastUpdateAt;

    public function __construct(
        int $id,
        string $ref,
        ?string $name,
        float $purchasePrice,
        float $sellingPrice,
        string $createdAt,
        ?int $quantity,
        CategoryDto $category,
        ?string $lastUpdateAt = null
    ) {
        $this->id = $id;
        $this->ref = $ref;
        $this->name = $name;
        $this->purchasePrice = $purchasePrice;
        $this->sellingPrice = $sellingPrice;
        $this->createdAt = $createdAt;
        $this->quantity = $quantity;
        $this->category = $category;
        $this->lastUpdateAt = $lastUpdateAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPurchasePrice(): float
    {
        return $this->purchasePrice;
    }

    public function getSellingPrice(): float
    {
        return $this->sellingPrice;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getCategory(): CategoryDto
    {
        return $this->category;
    }

    public function getLastUpdateAt(): ?string
    {
        return $this->lastUpdateAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'ref' => $this->ref,
            'name' => $this->name,
            'purchasePrice' => $this->purchasePrice,
            'sellingPrice' => $this->sellingPrice,
            'createdAt' => $this->createdAt,
            'quantity' => $this->quantity,
            'category' => $this->category->toArray(),
            'lastUpdateAt' => $this->lastUpdateAt,
        ];
    }
}
