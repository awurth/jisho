<?php

declare(strict_types=1);

namespace App\Common\Security;

use App\Common\Entity\User;
use App\Common\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Override;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class GoogleAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly EntityManagerInterface $entityManager,
        private readonly JWTTokenManagerInterface $JWTTokenManager,
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Override]
    public function supports(Request $request): bool
    {
        return $request->attributes->getString('_route') === 'connect_google_check';
    }

    #[Override]
    public function authenticate(Request $request): Passport
    {
        /** @var GoogleClient $client */
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client): User {
                /** @var GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);

                $user = $this->userRepository->findOneBy(['email' => $googleUser->getEmail()]);

                if (!$user instanceof User) {
                    $user = new User();

                    if (null === $googleUser->getEmail() || '' === $googleUser->getEmail()) {
                        throw new AuthenticationException('Email not found.');
                    }

                    $user->setEmail($googleUser->getEmail());
                }

                $user->setName($googleUser->getName());
                $user->setAvatarUrl($googleUser->getAvatar() ?? '');

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            }),
        );
    }

    #[Override]
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): JsonResponse
    {
        $jwt = $this->JWTTokenManager->create($token->getUser());

        return new JsonResponse(['token' => $jwt]);
    }

    #[Override]
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], Response::HTTP_FORBIDDEN);
    }
}
