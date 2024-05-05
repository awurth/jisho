<?php

declare(strict_types=1);

namespace App\Search\Indexation;

use App\Entity\Dictionary\Entry;
use App\Repository\Dictionary\EntryRepository;
use App\Search\DataTransformer\EntryDataTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Meilisearch\Client;
use function iterator_count;

final readonly class DictionaryIndexer
{
    private const int BATCH_SIZE = 500;

    public function __construct(
        private Client $searchClient,
        private EntityManagerInterface $entityManager,
        private EntryDataTransformer $entryDataTransformer,
        private EntryRepository $entryRepository,
    ) {
    }

    public function indexAll(): void
    {
        $offset = 0;
        while (iterator_count($entries = $this->entryRepository->getBatch($offset, self::BATCH_SIZE)) > 0) {
            $this->indexBatch(...$entries);

            $this->entityManager->clear();
            unset($entries);

            $offset += self::BATCH_SIZE;
        }
    }

    public function indexBatch(Entry ...$entries): void
    {
        $documents = $this->entryDataTransformer->transformToSearchArray(...$entries);

        $this->searchClient->index('dictionary')->addDocuments($documents, 'sequenceId');
    }
}
