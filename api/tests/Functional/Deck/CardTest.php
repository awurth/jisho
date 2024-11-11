<?php

declare(strict_types=1);

namespace App\Tests\Functional\Deck;

use App\Common\Foundry\Factory\Deck\CardFactory;
use App\Common\Foundry\Factory\Deck\DeckFactory;
use App\Common\Foundry\Factory\Dictionary\EntryFactory;
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
        $card = CardFactory::createOne([
            'entry' => EntryFactory::new()->empty()->create(),
        ]);

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

    public function testGetCardItemOfAnotherUserDeck(): void
    {
        $user = UserFactory::createOne();
        $card = CardFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('GET', "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}");

        self::assertResponseStatusCodeSame(403);
    }

    public function testGetCardItemResult(): void
    {
        $card = CardFactory::createOne([
            'entry' => EntryFactory::new()->empty()->create(),
        ]);

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

    public function testPostCardOnAnotherUserDeck(): void
    {
        $user = UserFactory::createOne();
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('POST', "/api/decks/{$deck->getId()}/cards", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(403);
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

    public function testPostCardSuccess(): void
    {
        $entry = EntryFactory::new()->empty()->create();
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('POST', "/api/decks/{$deck->getId()}/cards", [
            'json' => [
                'entry' => "/api/dictionary/entries/{$entry->getId()}",
            ],
        ]);

        CardFactory::assert()->count(1);
        $card = CardFactory::first();

        self::assertResponseStatusCodeSame(201);
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

    public function testDeleteCardWhenNotAuthenticated(): void
    {
        $card = CardFactory::createOne();

        $client = self::createClient();
        $client->request('DELETE', "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}");

        self::assertResponseStatusCodeSame(401);
        CardFactory::assert()->exists($card->getId());
    }

    public function testDeleteCardOfAnotherUserDeck(): void
    {
        $user = UserFactory::createOne();
        $card = CardFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('DELETE', "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}");

        self::assertResponseStatusCodeSame(403);
        CardFactory::assert()->exists($card->getId());
    }

    public function testDeleteDeckWithInvalidId(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->request('DELETE', "/api/decks/{$deck->getId()}/cards/1");

        self::assertResponseStatusCodeSame(404);
    }

    public function testDeleteDeckSuccess(): void
    {
        $card = CardFactory::createOne();

        $client = self::createClient();
        $client->loginUser($card->deck->owner);
        $client->request('DELETE', "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}");

        self::assertResponseStatusCodeSame(204);
        CardFactory::assert()->notExists($card->getId());
    }
}
