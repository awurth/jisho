<?php

declare(strict_types=1);

namespace App\Tests\Functional\Deck;

use App\Common\Foundry\Factory\Deck\CardFactory;
use App\Common\Foundry\Factory\Deck\DeckFactory;
use App\Common\Foundry\Factory\UserFactory;
use App\Tests\Functional\ApiTestCase;
use DateTimeInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class CardTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetCardCollectionWhenNotAuthenticated(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->request('GET', "/api/decks/{$deck->getId()}/cards");

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetCardCollectionResult(): void
    {
        $card = CardFactory::createOne();

        CardFactory::createOne();

        $client = self::createClient();
        $client->loginUser($card->deck->owner);
        $client->request('GET', "/api/decks/{$card->deck->getId()}/cards");

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            [
                'id' => (string) $card->getId(),
                'entry' => [
                    'id' => (string) $card->entry->getId(),
                    'kanji' => [],
                    'readings' => [],
                    'senses' => [],
                ],
                'addedAt' => $card->addedAt->format(DateTimeInterface::ATOM),
            ],
        ]);
    }

    public function testGetCardItemWithInvalidId(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->request('GET', "/api/decks/{$deck->getId()}/cards/1");

        self::assertResponseStatusCodeSame(404);
    }

    public function testGetCardItemWhenNotAuthenticated(): void
    {
        $card = CardFactory::createOne();

        $client = self::createClient();
        $client->request('GET', "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}");

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetCardItemOfAnotherUser(): void
    {
        $user = UserFactory::createOne();
        $card = CardFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('GET', "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}");

        self::assertResponseStatusCodeSame(404);
    }

    public function testGetCardItemResult(): void
    {
        $card = CardFactory::createOne();

        $client = self::createClient();
        $client->loginUser($card->deck->owner);
        $client->request('GET', "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}");

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            'id' => (string) $card->getId(),
            'entry' => [
                'id' => (string) $card->entry->getId(),
                'kanji' => [],
                'readings' => [],
                'senses' => [],
            ],
            'addedAt' => $card->addedAt->format(DateTimeInterface::ATOM),
        ]);
    }

    public function testPostCardWhenNotAuthenticated(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->request('POST', "/api/decks/{$deck->getId()}/cards", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(401);
    }

    public function testPostCardWithExistingEntry(): void
    {
        $existingCard = CardFactory::createOne();

        $client = self::createClient();
        $client->loginUser($existingCard->deck->owner);
        $client->request('POST', "/api/decks/{$existingCard->deck->getId()}/cards", [
            'json' => [
                'entry' => "/api/dictionary/entries/{$existingCard->entry->getId()}",
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'entry',
                    'message' => 'This entry is already in the deck.',
                ],
            ],
        ]);
    }

    // public function testPostDeckSuccess(): void
    // {
    //     $user = UserFactory::createOne();
    //
    //     $client = self::createClient();
    //     $client->loginUser($user);
    //     $client->request('POST', '/api/decks', [
    //         'json' => [
    //             'name' => 'foo',
    //         ],
    //     ]);
    //
    //     self::assertResponseStatusCodeSame(201);
    //     self::assertJsonContains([
    //         'name' => 'foo',
    //     ]);
    // }
    //
    // public function testPatchDeckWhenNotAuthenticated(): void
    // {
    //     $deck = DeckFactory::createOne();
    //
    //     $client = self::createClient();
    //     self::patch($client, "/api/decks/{$deck->getId()}", []);
    //
    //     self::assertResponseStatusCodeSame(401);
    // }
    //
    // public function testPatchDeckOfAnotherUser(): void
    // {
    //     $user = UserFactory::createOne();
    //     $deck = DeckFactory::createOne();
    //
    //     $client = self::createClient();
    //     $client->loginUser($user);
    //     self::patch($client, "/api/decks/{$deck->getId()}", []);
    //
    //     self::assertResponseStatusCodeSame(403);
    // }
    //
    // public function testPatchDeckWithInvalidId(): void
    // {
    //     $client = self::createClient();
    //     self::patch($client, '/api/decks/1', []);
    //
    //     self::assertResponseStatusCodeSame(404);
    // }
    //
    // public function testPatchDeckWithExistingName(): void
    // {
    //     $user = UserFactory::createOne();
    //
    //     $deck = DeckFactory::createOne([
    //         'name' => 'foo',
    //         'owner' => $user,
    //     ]);
    //
    //     DeckFactory::createOne([
    //         'name' => 'bar',
    //         'owner' => $user,
    //     ]);
    //
    //     $client = self::createClient();
    //     $client->loginUser($user);
    //     self::patch($client, "/api/decks/{$deck->getId()}", [
    //         'name' => 'foo',
    //     ]);
    //
    //     self::assertResponseStatusCodeSame(200);
    //
    //     self::patch($client, "/api/decks/{$deck->getId()}", [
    //         'name' => 'bar',
    //     ]);
    //
    //     self::assertResponseStatusCodeSame(422);
    //     self::assertJsonContains([
    //         'violations' => [
    //             [
    //                 'propertyPath' => 'name',
    //                 'message' => 'This name is already taken. Please choose another one.',
    //             ],
    //         ],
    //     ]);
    // }
    //
    // public function testPatchDeckSuccess(): void
    // {
    //     $deck = DeckFactory::createOne([
    //         'name' => 'foo',
    //     ]);
    //
    //     $client = self::createClient();
    //     $client->loginUser($deck->owner);
    //     self::patch($client, "/api/decks/{$deck->getId()}", [
    //         'name' => 'bar',
    //     ]);
    //
    //     self::assertResponseStatusCodeSame(200);
    //     self::assertJsonEquals([
    //         'id' => (string) $deck->getId(),
    //         'name' => 'bar',
    //         'createdAt' => $deck->createdAt->format(DateTimeInterface::ATOM),
    //     ]);
    // }
    //
    // public function testDeleteDeckWhenNotLoggedIn(): void
    // {
    //     $deck = DeckFactory::createOne();
    //
    //     $client = self::createClient();
    //     $client->request('DELETE', "/api/decks/{$deck->getId()}");
    //
    //     self::assertResponseStatusCodeSame(401);
    //     DeckFactory::assert()->exists($deck->getId());
    // }
    //
    // public function testDeleteDeckOfAnotherUser(): void
    // {
    //     $user = UserFactory::createOne();
    //     $deck = DeckFactory::createOne();
    //
    //     $client = self::createClient();
    //     $client->loginUser($user);
    //     $client->request('DELETE', "/api/decks/{$deck->getId()}");
    //
    //     self::assertResponseStatusCodeSame(403);
    //     DeckFactory::assert()->exists($deck->getId());
    // }
    //
    // public function testDeleteDeckWithInvalidId(): void
    // {
    //     $client = self::createClient();
    //     $client->request('DELETE', '/api/decks/1');
    //
    //     self::assertResponseStatusCodeSame(404);
    // }
    //
    // public function testDeleteDeckSuccess(): void
    // {
    //     $deck = DeckFactory::createOne();
    //
    //     $client = self::createClient();
    //     $client->loginUser($deck->owner);
    //     $client->request('DELETE', "/api/decks/{$deck->getId()}");
    //
    //     self::assertResponseStatusCodeSame(204);
    //     DeckFactory::assert()->notExists($deck->getId());
    // }
}
