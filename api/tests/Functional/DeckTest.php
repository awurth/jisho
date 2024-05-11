<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Common\Factory\DeckFactory;
use App\Common\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class DeckTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetCollectionWhenNotAuthenticated(): void
    {
        $client = self::createClient();
        $client->request('GET', '/api/decks');

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetCollectionWhenAuthenticated(): void
    {
        $user = UserFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user->object());
        $client->request('GET', '/api/decks');

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([]);
    }

    public function testGetCollectionResult(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $response = $client->request('GET', '/api/decks');

        $result = $response->toArray();

        self::assertResponseStatusCodeSame(200);
        self::assertCount(1, $result);
        self::assertJsonContains([
            [
                'id' => (string) $deck->getId(),
                'name' => $deck->name,
            ],
        ]);
    }

    public function testGetItemWithInvalidId(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('GET', '/api/decks/1');

        self::assertResponseStatusCodeSame(404);
    }
}
