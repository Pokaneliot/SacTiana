<?php

namespace App\Controller\ProductCategory;

use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\ProductCategory\ProductCategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/categories/{id}', name: 'category_get', methods: ['GET'])]
class GetProductCategoryController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryServiceInterface $categoryService,
        private readonly JsonResponseServiceInterface $jsonResponseService
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getCategory($id);

            if (!$category) {
                return $this->jsonResponseService->error(
                    'Category not found',
                    Response::HTTP_NOT_FOUND
                );
            }

            return $this->jsonResponseService->success($category->toArray());
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while retrieving the category',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
