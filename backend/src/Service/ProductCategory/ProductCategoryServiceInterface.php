<?php

namespace App\Service\ProductCategory;

use App\Dto\ProductCategory\Request\CreateProductCategoryRequestDto;
use App\Dto\ProductCategory\Request\UpdateProductCategoryRequestDto;
use App\Dto\ProductCategory\Response\ProductCategoryResponseDto;

interface ProductCategoryServiceInterface
{
    /**
     * Create a new product category
     */
    public function createCategory(CreateProductCategoryRequestDto $dto): ProductCategoryResponseDto;

    /**
     * Update a product category
     */
    public function updateCategory(int $id, UpdateProductCategoryRequestDto $dto): ProductCategoryResponseDto;

    /**
     * Get a single category by ID
     */
    public function getCategory(int $id): ?ProductCategoryResponseDto;

    /**
     * Get all categories
     * 
     * @return ProductCategoryResponseDto[]
     */
    public function getAllCategories(): array;

    /**
     * Delete a category
     */
    public function deleteCategory(int $id): bool;
}
