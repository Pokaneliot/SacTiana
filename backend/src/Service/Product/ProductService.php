<?php

namespace App\Service\Product;

use App\Dto\Product\Request\CreateProductRequestDto;
use App\Dto\Product\Request\ProductFilterDto;
use App\Dto\Product\Request\UpdateProductRequestDto;
use App\Dto\Product\Response\CategoryDto;
use App\Dto\Product\Response\ProductResponseDto;
use App\Entity\Product;
use App\Entity\ProductUpdate;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductStockRepository;
use App\Repository\ProductUpdateRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductRepository $productRepository,
        private readonly ProductCategoryRepository $categoryRepository,
        private readonly ProductUpdateRepository $productUpdateRepository,
        private readonly ProductStockRepository $productStockRepository
    ) {
    }

    public function createProduct(CreateProductRequestDto $dto): ProductResponseDto
    {
        $category = $this->categoryRepository->find($dto->getCategoryId());
        if (!$category) {
            throw new \RuntimeException('Category not found');
        }

        $product = new Product();
        $product->setRef($dto->getRef());
        $product->setName($dto->getName());
        $product->setPurchasePrice((string)$dto->getPurchasePrice());
        $product->setSellingPrice((string)$dto->getSellingPrice());
        $product->setCreatedAt(new \DateTime());
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->buildProductResponseDto($product);
    }

    public function updateProduct(int $id, UpdateProductRequestDto $dto): ProductResponseDto
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new \RuntimeException('Product not found');
        }

        // Create product update entry with only changed fields
        $productUpdate = new ProductUpdate();
        $productUpdate->setProduct($product);
        $productUpdate->setUpdateAt(new \DateTime());

        // Only set fields that were provided in the update
        if ($dto->getRef() !== null) {
            $productUpdate->setRef($dto->getRef());
        }
        if ($dto->getName() !== null) {
            $productUpdate->setName($dto->getName());
        }
        if ($dto->getPurchasePrice() !== null) {
            $productUpdate->setPurchasePrice((string)$dto->getPurchasePrice());
        }
        if ($dto->getSellingPrice() !== null) {
            $productUpdate->setSellingPrice((string)$dto->getSellingPrice());
        }

        $this->entityManager->persist($productUpdate);
        $this->entityManager->flush();

        // Reload product with updates
        $product = $this->productRepository->findProductWithStockAndUpdates($id);
        return $this->buildProductResponseDto($product);
    }

    public function getProduct(int $id): ?ProductResponseDto
    {
        $product = $this->productRepository->findProductWithStockAndUpdates($id);
        
        if (!$product) {
            return null;
        }

        return $this->buildProductResponseDto($product);
    }

    public function getAllProducts(ProductFilterDto $filterDto): array
    {
        $filters = $filterDto->toArray();
        $products = $this->productRepository->findProductsWithStockAndUpdates($filters);

        return array_map(
            fn(Product $product) => $this->buildProductResponseDto($product),
            $products
        );
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            return false;
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Build ProductResponseDto from Product entity
     * Applies latest update values if available and gets stock quantity
     */
    private function buildProductResponseDto(Product $product): ProductResponseDto
    {
        // Get base values from product
        $ref = $product->getRef();
        $name = $product->getName();
        $purchasePrice = (float)$product->getPurchasePrice();
        $sellingPrice = (float)$product->getSellingPrice();
        $lastUpdateAt = null;

        // Check for latest update and override values if present
        $latestUpdate = $this->productUpdateRepository->findLatestByProduct($product->getId());
        if ($latestUpdate) {
            // Override with updated values (only if not null in update)
            if ($latestUpdate->getRef() !== null) {
                $ref = $latestUpdate->getRef();
            }
            if ($latestUpdate->getName() !== null) {
                $name = $latestUpdate->getName();
            }
            if ($latestUpdate->getPurchasePrice() !== null) {
                $purchasePrice = (float)$latestUpdate->getPurchasePrice();
            }
            if ($latestUpdate->getSellingPrice() !== null) {
                $sellingPrice = (float)$latestUpdate->getSellingPrice();
            }
            $lastUpdateAt = $latestUpdate->getUpdateAt()->format('Y-m-d');
        }

        // Get stock quantity
        $stock = $this->productStockRepository->findByProduct($product->getId());
        $quantity = $stock ? $stock->getQuantity() : null;

        // Build category DTO
        $categoryDto = new CategoryDto(
            $product->getCategory()->getId(),
            $product->getCategory()->getName()
        );

        return new ProductResponseDto(
            $product->getId(),
            $ref,
            $name,
            $purchasePrice,
            $sellingPrice,
            $product->getCreatedAt()->format('Y-m-d'),
            $quantity,
            $categoryDto,
            $lastUpdateAt
        );
    }
}
