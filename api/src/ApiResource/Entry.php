<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Entity\Dictionary;
use App\State\EntryProvider;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/dictionaries/{dictionaryId}/entries',
    operations: [
        new GetCollection(),
    ],
    uriVariables: ['dictionaryId' => new Link(fromClass: Dictionary::class)],
    security: 'is_granted("ROLE_USER")',
    provider: EntryProvider::class,
)]
#[ApiResource(
    uriTemplate: '/dictionaries/{dictionaryId}/entries/{id}',
    operations: [
        new Get(),
    ],
    uriVariables: [
        'dictionaryId' => new Link(fromClass: Dictionary::class),
        'id' => new Link(fromClass: Entry::class),
    ],
    security: 'is_granted("ROLE_USER")',
    provider: EntryProvider::class,
)]
final class Entry
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
