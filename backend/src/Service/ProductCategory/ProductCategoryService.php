<?php

namespace App\Service\ProductCategory;

use App\Dto\ProductCategory\Request\CreateProductCategoryRequestDto;
use App\Dto\ProductCategory\Request\UpdateProductCategoryRequestDto;
use App\Dto\ProductCategory\Response\ProductCategoryResponseDto;
use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductCategoryService implements ProductCategoryServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductCategoryRepository $categoryRepository
    ) {
    }

    public function createCategory(CreateProductCategoryRequestDto $dto): ProductCategoryResponseDto
    {
        // Check if category with same name already exists
        $existing = $this->categoryRepository->findOneBy(['name' => $dto->getName()]);
        if ($existing) {
            throw new \RuntimeException('Category with this name already exists');
        }

        $category = new ProductCategory();
        $category->setName($dto->getName());

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->buildResponseDto($category);
    }

    public function updateCategory(int $id, UpdateProductCategoryRequestDto $dto): ProductCategoryResponseDto
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new \RuntimeException('Category not found');
        }

        // Check if another category with same name exists
        $existing = $this->categoryRepository->findOneBy(['name' => $dto->getName()]);
        if ($existing && $existing->getId() !== $id) {
            throw new \RuntimeException('Category with this name already exists');
        }

        $category->setName($dto->getName());
        $this->entityManager->flush();

        return $this->buildResponseDto($category);
    }

    public function getCategory(int $id): ?ProductCategoryResponseDto
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return null;
        }

        return $this->buildResponseDto($category);
    }

    public function getAllCategories(): array
    {
        $categories = $this->categoryRepository->findAll();

        return array_map(
            fn(ProductCategory $category) => $this->buildResponseDto($category),
            $categories
        );
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return false;
        }

        // Check if category has products
        if ($category->getProducts()->count() > 0) {
            throw new \RuntimeException('Cannot delete category with existing products');
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return true;
    }

    private function buildResponseDto(ProductCategory $category): ProductCategoryResponseDto
    {
        return new ProductCategoryResponseDto(
            $category->getId(),
            $category->getName()
        );
    }
}
