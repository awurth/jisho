<?php

declare(strict_types=1);

namespace App\Deck\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Entity\User;
use App\Deck\State\DeckProcessor;
use App\Deck\State\DeckProvider;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/decks',
            normalizationContext: [
                'groups' => ['deck:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("ROLE_USER")',
            provider: DeckProvider::class,
        ),
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
            normalizationContext: [
                'groups' => ['deck:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("DECK_DELETE", object)',
            provider: DeckProvider::class,
            processor: DeckProcessor::class,
        ),
    ],
)]
#[UniqueEntity(
    fields: ['owner', 'name'],
    message: 'This name is already taken. Please choose another one.',
    entityClass: DeckEntity::class,
    errorPath: 'name',
    identifierFieldNames: ['id'],
)]
final class Deck
{
    public DeckEntity $entity;

    #[Groups('deck:read')]
    public ?Uuid $id = null;

    public User $owner;

    #[Groups(['deck:read', 'deck:write'])]
    #[NotBlank]
    #[Length(max: 50)]
    public string $name;

    #[Groups(['deck:read'])]
    public DateTimeImmutable $createdAt;
}
