<?php

namespace App\Controller\Product;

use App\Dto\Product\Request\UpdateProductRequestDto;
use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\Product\ProductServiceInterface;
use App\Service\Validation\ValidationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/products/{id}', name: 'product_update', methods: ['PUT', 'PATCH'])]
class UpdateProductController extends AbstractController
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
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
                UpdateProductRequestDto::class,
                'json'
            );

            $errors = $this->validationService->validate($dto);
            if (!empty($errors)) {
                return $this->jsonResponseService->validationError($errors);
            }

            $product = $this->productService->updateProduct($id, $dto);

            return $this->jsonResponseService->success($product->toArray());
        } catch (\RuntimeException $e) {
            return $this->jsonResponseService->error(
                $e->getMessage(),
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while updating the product',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
