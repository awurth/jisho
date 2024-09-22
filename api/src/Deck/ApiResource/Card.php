<?php

declare(strict_types=1);

namespace App\Deck\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Common\Entity\Deck\Card as CardEntity;
use App\Deck\State\CardProcessor;
use App\Deck\State\CardProvider;
use App\Dictionary\ApiResource\Entry;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/decks/{deckId}/entries',
            uriVariables: [
                'deckId' => new Link(fromClass: Deck::class),
            ],
            normalizationContext: [
                'groups' => ['card:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("ROLE_USER")',
            provider: CardProvider::class,
        ),
        new Get(
            uriTemplate: '/decks/{deckId}/entries/{id}',
            uriVariables: [
                'deckId' => new Link(fromClass: Deck::class),
                'id' => new Link(fromClass: Card::class),
            ],
            normalizationContext: [
                'groups' => ['card:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("CARD_VIEW", object)',
            provider: CardProvider::class,
        ),
        new Post(
            uriTemplate: '/decks/{deckId}/entries',
            uriVariables: [
                'deckId' => new Link(fromClass: Deck::class),
            ],
            normalizationContext: [
                'groups' => ['card:read'],
                'openapi_definition_name' => 'Read',
            ],
            denormalizationContext: [
                'groups' => ['card:write'],
                'openapi_definition_name' => 'Write',
            ],
            security: 'is_granted("CARD_CREATE", object.deck)',
            provider: CardProvider::class,
            processor: CardProcessor::class,
        ),
        // new Patch(
        //     uriTemplate: '/decks/{id}',
        //     uriVariables: [
        //         'id' => new Link(fromClass: Card::class),
        //     ],
        //     normalizationContext: [
        //         'groups' => ['deck:read'],
        //         'openapi_definition_name' => 'Read',
        //     ],
        //     denormalizationContext: [
        //         'groups' => ['deck:write'],
        //         'openapi_definition_name' => 'Write',
        //     ],
        //     security: 'is_granted("DECK_EDIT", object)',
        //     provider: DeckProvider::class,
        //     processor: DeckProcessor::class,
        // ),
        new Delete(
            uriTemplate: '/decks/{deckId}/entries/{id}',
            uriVariables: [
                'deckId' => new Link(fromClass: Deck::class),
                'id' => new Link(fromClass: Card::class),
            ],
            normalizationContext: [
                'groups' => ['card:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("CARD_DELETE", object)',
            provider: CardProvider::class,
            processor: CardProcessor::class,
        ),
    ],
)]
final class Card
{
    public CardEntity $entity;

    #[Groups('card:read')]
    public Uuid $id;

    public Deck $deck;

    #[Groups(['card:read', 'card:write'])]
    public Entry $entry;

    #[Groups(['card:read'])]
    public DateTimeImmutable $addedAt;
}
