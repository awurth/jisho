<?php

namespace App\Common\Controller;

use App\Common\Entity\User;
use App\Common\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Google\Client;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\HeaderAccessTokenExtractor;
use function is_array;

#[AsController]
#[Route('/connect/google', name: 'connect_google', methods: ['POST'])]
final readonly class GoogleConnectAction
{
    public function __construct(
        private Client $client,
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $JWTTokenManager,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $accessTokenExtractor = new HeaderAccessTokenExtractor();
        $accessToken = $accessTokenExtractor->extractAccessToken($request);

        if (null === $accessToken || '' === $accessToken) {
            throw new BadCredentialsException();
        }

        $response = $this->client->verifyIdToken($accessToken);

        if (!is_array($response)) {
            throw new BadCredentialsException();
        }

        $email = $response['email'];
        $name = $response['name'];
        $picture = $response['picture'];

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user instanceof User) {
            $user = new User();
            $user->setEmail($email);
        }

        $user->setName($name);
        $user->setAvatarUrl($picture ?? '');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $jwt = $this->JWTTokenManager->create($user);

        return new JsonResponse([
            'token' => $jwt,
            'name' => $user->getName(),
            'avatarUrl' => $user->getAvatarUrl(),
        ]);
    }
}
