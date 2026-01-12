<?php

namespace App\Controller\User;

use App\Dto\User\Request\LoginRequestDto;
use App\Service\JsonResponse\JsonResponseServiceInterface;
use App\Service\Validation\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

#[Route('/api/auth/login', name: 'auth_login', methods: ['POST'])]
class LoginController extends AbstractController
{
    private JsonResponseServiceInterface $jsonResponseService;
    private ValidationService $validationService;

    public function __construct(
        JsonResponseServiceInterface $jsonResponseService,
        ValidationService $validationService
    ) {
        $this->jsonResponseService = $jsonResponseService;
        $this->validationService = $validationService;
    }

    public function __invoke(Request $request, #[CurrentUser] ?User $user): JsonResponse
    {
        // If user is already authenticated via the authenticator
        if ($user) {
            return $this->jsonResponseService->success([
                'id' => $user->getId(),
                'name' => $user->getName(),
                'login' => $user->getLogin(),
                'role' => $user->getRole(),
            ], 'Authentication successful');
        }

        // This shouldn't happen if json_login works, but as fallback
        return $this->jsonResponseService->error(
            'Authentication failed: Invalid credentials',
            401
        );
    }
}
