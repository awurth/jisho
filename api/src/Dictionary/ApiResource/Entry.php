<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Common\Entity\Dictionary\Entry as EntryEntity;
use App\Dictionary\State\EntryProvider;
use App\Dictionary\State\SearchProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/search/{query}',
            uriVariables: [
                'query' => 'query',
            ],
            shortName: 'Search',
            // paginationItemsPerPage: 30,
            // paginationMaximumItemsPerPage: GoogleBooksApiClient::MAX_RESULTS,
            // paginationClientItemsPerPage: true,
            provider: SearchProvider::class,
        ),
    ],
)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/dictionary/entries/{id}',
            uriVariables: [
                'id' => new Link(fromClass: Entry::class),
            ],
            shortName: 'DictionaryEntry',
            normalizationContext: [
                'groups' => ['entry:read'],
                'openapi_definition_name' => 'Read',
            ],
            provider: EntryProvider::class,
        ),
    ],
)]
final class Entry
{
    public EntryEntity $entity;

    /**
     * @param Kanji[]   $kanji
     * @param Reading[] $readings
     * @param Sense[]   $senses
     */
    public function __construct(
        #[Groups(['deck-entry:read', 'entry:read'])]
        public string $id,
        #[Groups(['deck-entry:read', 'entry:read'])]
        public array $kanji,
        #[Groups(['deck-entry:read', 'entry:read'])]
        public array $readings,
        #[Groups(['deck-entry:read', 'entry:read'])]
        public array $senses,
    ) {
    }
}
