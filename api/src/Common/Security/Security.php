<?php

declare(strict_types=1);

namespace App\Common\Security;

use App\Common\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class Security
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function getUser(): User
    {
        $user = $this->tokenStorage->getToken()?->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}
