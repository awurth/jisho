<?php

declare(strict_types=1);

namespace App\ApiResource\Dictionary;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Entity\Dictionary\Entry as EntryEntity;
use App\State\Dictionary\EntryProvider;
use App\State\Dictionary\SearchProvider;
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
        #[Groups('deck-entry:read')]
        public string $id,
        #[Groups('deck-entry:read')]
        public array $kanji,
        #[Groups('deck-entry:read')]
        public array $readings,
        #[Groups('deck-entry:read')]
        public array $senses,
    ) {
    }
}
