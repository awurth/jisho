<?php

declare(strict_types=1);

namespace App\Common\Controller;

use App\Common\Entity\User;
use App\Common\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Google\Client;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use UnexpectedValueException;
use function is_array;
use function is_string;
use function json_decode;

#[AsController]
#[Route('/connect/google', name: 'connect_google', methods: ['POST'], format: 'json')]
final readonly class GoogleConnectAction
{
    public function __construct(
        private Client $client,
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $JWTTokenManager,
        private UserRepository $userRepository,
        #[Autowire(param: 'kernel.environment')]
        private string $environment,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $json = json_decode(json: $request->getContent(), associative: true);

        if (!is_array($json)) {
            throw new BadCredentialsException();
        }

        $token = $json['token'] ?? '';

        if (!is_string($token) || '' === $token) {
            throw new BadCredentialsException();
        }

        try {
            $response = $this->client->verifyIdToken($token);
        } catch (UnexpectedValueException) {
            $response = false;
        }

        if (!is_array($response)) {
            if ('dev' !== $this->environment) {
                throw new BadCredentialsException();
            }

            $response = [
                'email' => 'alexis.wurth57@gmail.com',
                'name' => 'Alexis Wurth',
                'picture' => '',
            ];
        }

        $email = $response['email'];
        $name = $response['name'];
        $picture = $response['picture'];

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user instanceof User) {
            $user = new User();
            $user->email = $email;
        }

        $user->name = $name;
        $user->avatarUrl = $picture ?? '';

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $jwt = $this->JWTTokenManager->create($user);

        return new JsonResponse([
            'token' => $jwt,
            'name' => $user->name,
            'avatarUrl' => $user->avatarUrl,
        ]);
    }
}
