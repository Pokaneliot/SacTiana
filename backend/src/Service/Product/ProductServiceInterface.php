<?php

namespace App\Service\Product;

use App\Dto\Product\Request\CreateProductRequestDto;
use App\Dto\Product\Request\ProductFilterDto;
use App\Dto\Product\Request\UpdateProductRequestDto;
use App\Dto\Product\Response\ProductResponseDto;

interface ProductServiceInterface
{
    /**
     * Create a new product
     */
    public function createProduct(CreateProductRequestDto $dto): ProductResponseDto;

    /**
     * Update a product (creates entry in product_update table)
     */
    public function updateProduct(int $id, UpdateProductRequestDto $dto): ProductResponseDto;

    /**
     * Get a single product by ID with stock and latest updates
     */
    public function getProduct(int $id): ?ProductResponseDto;

    /**
     * Get all products with optional filtering
     * 
     * @return ProductResponseDto[]
     */
    public function getAllProducts(ProductFilterDto $filterDto): array;

    /**
     * Delete a product (cascade delete on related records)
     */
    public function deleteProduct(int $id): bool;
}
