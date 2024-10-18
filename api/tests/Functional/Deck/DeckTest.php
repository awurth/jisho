<?php

declare(strict_types=1);

namespace App\Tests\Functional\Deck;

use App\Common\Factory\DeckFactory;
use App\Common\Factory\UserFactory;
use App\Tests\Functional\ApiTestCase;
use DateTimeInterface;
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
        $client->loginUser($user);
        $client->request('GET', '/api/decks');

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([]);
    }

    public function testGetCollectionResult(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('GET', '/api/decks');

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            [
                'id' => (string) $deck->getId(),
                'name' => $deck->name,
                'createdAt' => $deck->createdAt->format(DateTimeInterface::ATOM),
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

    public function testGetItemWhenNotAuthenticated(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->request('GET', "/api/decks/{$deck->getId()}");

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetItemOfAnotherUser(): void
    {
        $user = UserFactory::createOne();
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('GET', "/api/decks/{$deck->getId()}");

        self::assertResponseStatusCodeSame(403);
    }

    public function testGetItemResult(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('GET', "/api/decks/{$deck->getId()}");

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            'id' => (string) $deck->getId(),
            'name' => $deck->name,
            'createdAt' => $deck->createdAt->format(DateTimeInterface::ATOM),
        ]);
    }

    public function testPostWhenNotAuthenticated(): void
    {
        $client = self::createClient();
        $client->request('POST', '/api/decks', [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(401);
    }

    public function testPostWithExistingName(): void
    {
        $deck = DeckFactory::createOne([
            'name' => 'foo',
        ]);

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('POST', '/api/decks', [
            'json' => [
                'name' => 'foo',
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'name',
                    'message' => 'This name is already taken. Please choose another one.',
                ],
            ],
        ]);
    }

    public function testPostSuccess(): void
    {
        $user = UserFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('POST', '/api/decks', [
            'json' => [
                'name' => 'foo',
            ],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertJsonContains([
            'name' => 'foo',
        ]);
    }

    public function testPatchWhenNotAuthenticated(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        self::patch($client, "/api/decks/{$deck->getId()}", []);

        self::assertResponseStatusCodeSame(401);
    }

    public function testPatchOfAnotherUser(): void
    {
        $user = UserFactory::createOne();
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        self::patch($client, "/api/decks/{$deck->getId()}", []);

        self::assertResponseStatusCodeSame(403);
    }

    public function testPatchWithInvalidId(): void
    {
        $client = self::createClient();
        self::patch($client, '/api/decks/1', []);

        self::assertResponseStatusCodeSame(404);
    }

    public function testPatchWithExistingName(): void
    {
        $user = UserFactory::createOne();

        $deck = DeckFactory::createOne([
            'name' => 'foo',
            'owner' => $user,
        ]);

        DeckFactory::createOne([
            'name' => 'bar',
            'owner' => $user,
        ]);

        $client = self::createClient();
        $client->loginUser($user);
        self::patch($client, "/api/decks/{$deck->getId()}", [
            'name' => 'foo',
        ]);

        self::assertResponseStatusCodeSame(200);

        self::patch($client, "/api/decks/{$deck->getId()}", [
            'name' => 'bar',
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'name',
                    'message' => 'This name is already taken. Please choose another one.',
                ],
            ],
        ]);
    }

    public function testPatchSuccess(): void
    {
        $deck = DeckFactory::createOne([
            'name' => 'foo',
        ]);

        $client = self::createClient();
        $client->loginUser($deck->owner);
        self::patch($client, "/api/decks/{$deck->getId()}", [
            'name' => 'bar',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            'id' => (string) $deck->getId(),
            'name' => 'bar',
            'createdAt' => $deck->createdAt->format(DateTimeInterface::ATOM),
        ]);
    }

    public function testDeleteWhenNotLoggedIn(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->request('DELETE', "/api/decks/{$deck->getId()}");

        self::assertResponseStatusCodeSame(401);
        DeckFactory::assert()->exists($deck->getId());
    }

    public function testDeleteOfAnotherUser(): void
    {
        $user = UserFactory::createOne();
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('DELETE', "/api/decks/{$deck->getId()}");

        self::assertResponseStatusCodeSame(403);
        DeckFactory::assert()->exists($deck->getId());
    }

    public function testDeleteWithInvalidId(): void
    {
        $client = self::createClient();
        $client->request('DELETE', '/api/decks/1');

        self::assertResponseStatusCodeSame(404);
    }

    public function testDeleteSuccess(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('DELETE', "/api/decks/{$deck->getId()}");

        self::assertResponseStatusCodeSame(204);
        DeckFactory::assert()->notExists($deck->getId());
    }
}
