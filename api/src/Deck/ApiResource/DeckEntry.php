<?php

declare(strict_types=1);

namespace App\Deck\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Common\Entity\Deck\DeckEntry as DeckEntryEntity;
use App\Deck\State\DeckEntryProcessor;
use App\Deck\State\DeckEntryProvider;
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
                'groups' => ['deck-entry:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("ROLE_USER")',
            provider: DeckEntryProvider::class,
        ),
        new Get(
            uriTemplate: '/decks/{deckId}/entries/{id}',
            uriVariables: [
                'deckId' => new Link(fromClass: Deck::class),
                'id' => new Link(fromClass: DeckEntry::class),
            ],
            normalizationContext: [
                'groups' => ['deck-entry:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("DECK_ENTRY_VIEW", object)',
            provider: DeckEntryProvider::class,
        ),
        new Post(
            uriTemplate: '/decks/{deckId}/entries',
            uriVariables: [
                'deckId' => new Link(fromClass: Deck::class),
            ],
            normalizationContext: [
                'groups' => ['deck-entry:read'],
                'openapi_definition_name' => 'Read',
            ],
            denormalizationContext: [
                'groups' => ['deck-entry:write'],
                'openapi_definition_name' => 'Write',
            ],
            security: 'is_granted("DECK_ENTRY_CREATE", object.deck)',
            provider: DeckEntryProvider::class,
            processor: DeckEntryProcessor::class,
        ),
        // new Patch(
        //     uriTemplate: '/decks/{id}',
        //     uriVariables: [
        //         'id' => new Link(fromClass: DeckEntry::class),
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
                'id' => new Link(fromClass: DeckEntry::class),
            ],
            normalizationContext: [
                'groups' => ['deck-entry:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("DECK_ENTRY_DELETE", object)',
            provider: DeckEntryProvider::class,
            processor: DeckEntryProcessor::class,
        ),
    ],
)]
final class DeckEntry
{
    public DeckEntryEntity $entity;

    #[Groups('deck-entry:read')]
    public Uuid $id;

    public Deck $deck;

    #[Groups(['deck-entry:read', 'deck-entry:write'])]
    public Entry $entry;

    #[Groups(['deck-entry:read'])]
    public DateTimeImmutable $addedAt;
}
