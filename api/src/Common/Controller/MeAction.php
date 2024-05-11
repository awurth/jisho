<?php

declare(strict_types=1);

namespace App\Common\Controller;

use App\Common\Entity\User;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[Route('/me', name: 'api_me', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
final readonly class MeAction
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->tokenStorage->getToken()?->getUser();

        if (!$user instanceof User) {
            throw new LogicException('User should be set');
        }

        return new JsonResponse([
            'name' => $user->getName(),
            'avatarUrl' => $user->getAvatarUrl(),
        ]);
    }
}
