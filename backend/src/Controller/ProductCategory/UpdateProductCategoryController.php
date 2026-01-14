<?php

namespace App\Controller\ProductCategory;

use App\Dto\ProductCategory\Request\UpdateProductCategoryRequestDto;
use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\ProductCategory\ProductCategoryServiceInterface;
use App\Service\Validation\ValidationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/categories/{id}', name: 'category_update', methods: ['PUT', 'PATCH'])]
#[IsGranted('ROLE_ADMIN')]
class UpdateProductCategoryController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryServiceInterface $categoryService,
        private readonly ValidationServiceInterface $validationService,
        private readonly JsonResponseServiceInterface $jsonResponseService,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                UpdateProductCategoryRequestDto::class,
                'json'
            );

            $errors = $this->validationService->validate($dto);
            if (!empty($errors)) {
                return $this->jsonResponseService->validationError($errors);
            }

            $category = $this->categoryService->updateCategory($id, $dto);

            return $this->jsonResponseService->success($category->toArray());
        } catch (\RuntimeException $e) {
            return $this->jsonResponseService->error(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while updating the category',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
