<?php

declare(strict_types=1);

namespace App\Common\Controller;

use App\Common\Security\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[Route('/me', name: 'api_me', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
final readonly class MeAction
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->security->getUser();

        return new JsonResponse([
            'name' => $user->getName(),
            'avatarUrl' => $user->getAvatarUrl(),
        ]);
    }
}
