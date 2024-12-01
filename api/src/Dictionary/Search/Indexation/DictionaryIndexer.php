<?php

declare(strict_types=1);

namespace App\Dictionary\Search\Indexation;

use App\Common\Entity\Dictionary\Entry;
use App\Common\Repository\Dictionary\EntryRepository;
use App\Dictionary\Search\DataTransformer\EntryDataTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Meilisearch\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use function iterator_count;

final readonly class DictionaryIndexer
{
    private const int BATCH_SIZE = 2000;

    public function __construct(
        private Client $searchClient,
        private EntityManagerInterface $entityManager,
        private EntryDataTransformer $entryDataTransformer,
        private EntryRepository $entryRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function indexAll(): void
    {
        $offset = 0;
        $batchesCount = 0;

        $stopwatch = new Stopwatch();

        while (iterator_count($entries = $this->entryRepository->getBatch($offset, self::BATCH_SIZE)) > 0) {
            $stopwatch->start('importBatch');

            $this->indexBatch(...$entries);

            $stopwatchEvent = $stopwatch->stop('importBatch');

            $this->entityManager->clear();
            unset($entries);

            $offset += self::BATCH_SIZE;
            ++$batchesCount;

            $this->logger->info('Indexed batch n°{batchesCount} ({entriesCount} total entries) in {duration} ms', [
                'batchesCount' => $batchesCount,
                'entriesCount' => $batchesCount * self::BATCH_SIZE,
                'duration' => $stopwatchEvent->getDuration(),
            ]);

            $stopwatch->reset();
        }
    }

    public function indexBatch(Entry ...$entries): void
    {
        $documents = $this->entryDataTransformer->transformToSearchArray(...$entries);

        $this->searchClient->index('dictionary')->addDocuments($documents);
    }
}
