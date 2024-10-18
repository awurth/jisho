<?php

declare(strict_types=1);

namespace App\Dictionary\Search\Indexation;

use App\Common\Entity\Dictionary\Entry;
use App\Common\Repository\Dictionary\EntryRepository;
use App\Dictionary\Search\DataTransformer\EntryDataTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Meilisearch\Client;
use function iterator_count;

final readonly class DictionaryIndexer
{
    private const int BATCH_SIZE = 2000;

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

        $this->searchClient->index('dictionary')->addDocuments($documents);
    }
}
