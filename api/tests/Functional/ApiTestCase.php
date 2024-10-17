<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Override;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function array_replace_recursive;

abstract class ApiTestCase extends BaseApiTestCase
{
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
}
