<?php

namespace App\Controller\User;

use App\Service\JsonResponse\JsonResponseServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth/logout', name: 'auth_logout', methods: ['POST'])]
class LogoutController extends AbstractController
{
    private JsonResponseServiceInterface $jsonResponseService;
    private Security $security;

    public function __construct(
        JsonResponseServiceInterface $jsonResponseService,
        Security $security
    ) {
        $this->jsonResponseService = $jsonResponseService;
        $this->security = $security;
    }

    public function __invoke(Request $request): JsonResponse
    {
        // Get current session and invalidate it
        $session = $request->getSession();
        $session->invalidate();
        
        // Logout the user
        $this->security->logout(false);
        
        return $this->jsonResponseService->success(
            null,
            'Logged out successfully'
        );
    }
}
