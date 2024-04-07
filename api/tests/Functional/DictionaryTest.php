<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\DictionaryFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class DictionaryTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetCollectionWhenNotAuthenticated(): void
    {
        $client = self::createClient();
        $client->request('GET', '/api/dictionaries');

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetCollectionWhenAuthenticated(): void
    {
        $user = UserFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user->object());
        $client->request('GET', '/api/dictionaries');

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([]);
    }

    public function testGetCollectionResult(): void
    {
        $dictionary = DictionaryFactory::createOne();

        $client = self::createClient();
        $client->loginUser($dictionary->owner);
        $response = $client->request('GET', '/api/dictionaries');

        $result = $response->toArray();

        self::assertResponseStatusCodeSame(200);
        self::assertCount(1, $result);
        self::assertJsonContains([
            [
                'id' => (string) $dictionary->getId(),
                'name' => $dictionary->name,
            ]
        ]);
    }

    public function testGetItemWithInvalidId(): void
    {
        $dictionary = DictionaryFactory::createOne();

        $client = self::createClient();
        $client->loginUser($dictionary->owner);
        $client->request('GET', '/api/dictionaries/1');

        self::assertResponseStatusCodeSame(404);
    }
}
