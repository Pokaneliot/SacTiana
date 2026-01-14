<?php

namespace App\Controller\Product;

use App\Dto\Product\Request\ProductFilterDto;
use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\Product\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products', name: 'product_list', methods: ['GET'])]
class ListProductsController extends AbstractController
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly JsonResponseServiceInterface $jsonResponseService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $filterDto = new ProductFilterDto();
            
            // Extract query parameters for filtering
            if ($request->query->has('ref')) {
                $filterDto->setRef($request->query->get('ref'));
            }
            if ($request->query->has('sellingPrice')) {
                $filterDto->setSellingPrice((float)$request->query->get('sellingPrice'));
            }
            if ($request->query->has('createdAt')) {
                $filterDto->setCreatedAt($request->query->get('createdAt'));
            }
            if ($request->query->has('categoryId')) {
                $filterDto->setCategoryId((int)$request->query->get('categoryId'));
            }

            $products = $this->productService->getAllProducts($filterDto);

            $productsArray = array_map(
                fn($product) => $product->toArray(),
                $products
            );

            return $this->jsonResponseService->success($productsArray);
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while retrieving products',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
