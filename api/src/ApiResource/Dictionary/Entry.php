<?php

declare(strict_types=1);

namespace App\ApiResource\Dictionary;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\Dictionary\SearchProvider;

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
final readonly class Entry
{
    /**
     * @param Kanji[]   $kanji
     * @param Reading[] $readings
     * @param Sense[]   $senses
     */
    public function __construct(
        public string $id,
        public array $kanji,
        public array $readings,
        public array $senses,
    ) {
    }
}
