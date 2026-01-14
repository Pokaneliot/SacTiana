<?php

namespace App\Controller\Product;

use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\Product\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products/{id}', name: 'product_get', methods: ['GET'])]
class GetProductController extends AbstractController
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly JsonResponseServiceInterface $jsonResponseService
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProduct($id);

            if (!$product) {
                return $this->jsonResponseService->error(
                    'Product not found',
                    Response::HTTP_NOT_FOUND
                );
            }

            return $this->jsonResponseService->success($product->toArray());
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while retrieving the product',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
