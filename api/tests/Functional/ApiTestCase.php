<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Override;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function array_replace_recursive;

abstract class ApiTestCase extends BaseApiTestCase
{
    protected static ?bool $alwaysBootKernel = false;

    /**
     * @phpstan-ignore-next-line
     */
    #[Override]
    protected static function createClient(array $kernelOptions = [], array $defaultOptions = []): Client
    {
        $defaultOptions = array_replace_recursive(
            [
                'headers' => [
                    'accept' => ['application/json'],
                ],
            ],
            $defaultOptions,
        );

        return parent::createClient($kernelOptions, $defaultOptions);
    }

    /**
     * @param array<string, mixed> $json
     */
    public static function patch(Client $client, string $url, array $json): ResponseInterface
    {
        return $client->request('PATCH', $url, [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => $json,
        ]);
    }

    public static function createAuthenticatedClient(UserInterface $user): Client
    {
        /** @var JWTEncoderInterface $encoder */
        $encoder = self::getContainer()->get(JWTEncoderInterface::class);

        return self::createClient(defaultOptions: [
            'auth_bearer' => $encoder->encode([
                'roles' => $user->getRoles(),
                'username' => $user->getUserIdentifier(),
            ]),
        ]);
    }
}
