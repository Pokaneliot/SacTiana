<?php

namespace App\Controller\ProductCategory;

use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\ProductCategory\ProductCategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/categories/{id}', name: 'category_delete', methods: ['DELETE'])]
#[IsGranted('ROLE_ADMIN')]
class DeleteProductCategoryController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryServiceInterface $categoryService,
        private readonly JsonResponseServiceInterface $jsonResponseService
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $deleted = $this->categoryService->deleteCategory($id);

            if (!$deleted) {
                return $this->jsonResponseService->error(
                    'Category not found',
                    Response::HTTP_NOT_FOUND
                );
            }

            return $this->jsonResponseService->success(
                ['message' => 'Category deleted successfully'],
                Response::HTTP_OK
            );
        } catch (\RuntimeException $e) {
            return $this->jsonResponseService->error(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while deleting the category',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
