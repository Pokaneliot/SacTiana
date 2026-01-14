<?php

namespace App\Controller\Product;

use App\Dto\Product\Request\CreateProductRequestDto;
use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\Product\ProductServiceInterface;
use App\Service\Validation\ValidationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/products', name: 'product_create', methods: ['POST'])]
class CreateProductController extends AbstractController
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly ValidationServiceInterface $validationService,
        private readonly JsonResponseServiceInterface $jsonResponseService,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                CreateProductRequestDto::class,
                'json'
            );

            $errors = $this->validationService->validate($dto);
            if (!empty($errors)) {
                return $this->jsonResponseService->validationError($errors);
            }

            $product = $this->productService->createProduct($dto);

            return $this->jsonResponseService->success(
                $product->toArray(),
                Response::HTTP_CREATED
            );
        } catch (\RuntimeException $e) {
            return $this->jsonResponseService->error(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->jsonResponseService->error(
                'An error occurred while creating the product',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
