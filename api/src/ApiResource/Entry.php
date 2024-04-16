<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Entity\Dictionary;
use App\State\EntryProcessor;
use App\State\EntryProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    security: 'is_granted("ENTRY_VIEW", object)',
    provider: EntryProvider::class,
)]
#[ApiResource(
    uriTemplate: '/dictionaries/{dictionaryId}/entries',
    operations: [
        new Post(),
    ],
    uriVariables: [
        'dictionaryId' => new Link(fromClass: Dictionary::class),
    ],
    denormalizationContext: [
        'groups' => ['entry:write'],
        'openapi_definition_name' => 'Write',
    ],
    security: 'is_granted("CREATE_DICTIONARY_ENTRY", object.dictionary)',
    provider: EntryProvider::class,
    processor: EntryProcessor::class,
)]
final class Entry
{
    #[ApiProperty(identifier: true)]
    public Uuid $id;

    public Dictionary $dictionary;

    #[NotBlank]
    #[Groups(['entry:write'])]
    public string $japanese;

    /**
     * @var string[]
     */
    #[Count(min: 1)]
    #[Groups(['entry:write'])]
    public array $french = [];

    /**
     * @var string[]
     */
    #[Groups(['entry:write'])]
    public array $tags = [];
}
