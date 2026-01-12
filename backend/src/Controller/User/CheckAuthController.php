<?php

namespace App\Controller\User;

use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth/check', name: 'auth_check', methods: ['GET'])]
class CheckAuthController extends AbstractController
{
    private JsonResponseServiceInterface $jsonResponseService;
    private UserServiceInterface $userService;

    public function __construct(
        JsonResponseServiceInterface $jsonResponseService,
        UserServiceInterface $userService
    ) {
        $this->jsonResponseService = $jsonResponseService;
        $this->userService = $userService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->jsonResponseService->error(
                'Not authenticated',
                401
            );
        }

        $userResponseDto = $this->userService->getUserResponseDto($user);

        return $this->jsonResponseService->success(
            $userResponseDto->toArray(),
            'User is authenticated'
        );
    }
}
