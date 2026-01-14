<?php

namespace App\Controller\ProductCategory;

use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\ProductCategory\ProductCategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/categories', name: 'category_list', methods: ['GET'])]
class ListProductCategoriesController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryServiceInterface $categoryService,
        private readonly JsonResponseServiceInterface $jsonResponseService
    ) {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getAllCategories();

            $categoriesArray = array_map(
                fn($category) => $category->toArray(),
                $categories
            );

            return $this->jsonResponseService->success($categoriesArray);
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while retrieving categories',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
