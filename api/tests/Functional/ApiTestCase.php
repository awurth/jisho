<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as BaseApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use function array_replace_recursive;

abstract class ApiTestCase extends BaseApiTestCase
{
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
}
