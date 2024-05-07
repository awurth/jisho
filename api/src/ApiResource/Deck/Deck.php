<?php

declare(strict_types=1);

namespace App\ApiResource\Deck;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Deck\Deck as DeckEntity;
use App\Entity\User;
use App\State\Deck\DeckProcessor;
use App\State\Deck\DeckProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/decks/{id}',
            uriVariables: [
                'id' => new Link(fromClass: Deck::class),
            ],
            normalizationContext: [
                'groups' => ['deck:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("DECK_VIEW", object)',
            provider: DeckProvider::class,
        ),
        new Post(
            uriTemplate: '/decks',
            normalizationContext: [
                'groups' => ['deck:read'],
                'openapi_definition_name' => 'Read',
            ],
            denormalizationContext: [
                'groups' => ['deck:write'],
                'openapi_definition_name' => 'Write',
            ],
            security: 'is_granted("DECK_CREATE")',
            processor: DeckProcessor::class,
        ),
        new Patch(
            uriTemplate: '/decks/{id}',
            uriVariables: [
                'id' => new Link(fromClass: Deck::class),
            ],
            normalizationContext: [
                'groups' => ['deck:read'],
                'openapi_definition_name' => 'Read',
            ],
            denormalizationContext: [
                'groups' => ['deck:write'],
                'openapi_definition_name' => 'Write',
            ],
            security: 'is_granted("DECK_EDIT", object)',
            provider: DeckProvider::class,
            processor: DeckProcessor::class,
        ),
        new Delete(
            uriTemplate: '/decks/{id}',
            uriVariables: [
                'id' => new Link(fromClass: Deck::class),
            ],
            security: 'is_granted("DECK_DELETE", object)',
            provider: DeckProvider::class,
            processor: DeckProcessor::class,
        ),
    ],
)]
final class Deck
{
    public DeckEntity $entity;

    #[Groups('deck:read')]
    public Uuid $id;

    public User $owner;

    #[Groups(['deck:read', 'deck:write'])]
    #[NotBlank]
    #[Length(max: 50)]
    public string $name;
}
