<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Entity\Dictionary;
use App\State\AssociationProvider;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/dictionaries/{dictionaryId}/associations',
    operations: [
        new GetCollection(),
    ],
    uriVariables: ['dictionaryId' => new Link(fromClass: Dictionary::class)],
    security: 'is_granted("ROLE_USER")',
    provider: AssociationProvider::class,
)]
#[ApiResource(
    uriTemplate: '/dictionaries/{dictionaryId}/associations/{id}',
    operations: [
        new Get(),
    ],
    uriVariables: [
        'dictionaryId' => new Link(fromClass: Dictionary::class),
        'id' => new Link(fromClass: Association::class),
    ],
    security: 'is_granted("ROLE_USER")',
    provider: AssociationProvider::class,
)]
final class Association
{
    #[ApiProperty(identifier: true)]
    public Uuid $id;

    public Dictionary $dictionary;

    public string $japanese;

    /**
     * @var string[]
     */
    public array $french = [];
}
