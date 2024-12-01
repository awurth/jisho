<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict;

use App\Common\Entity\Dictionary\Entry as EntryEntity;
use App\Common\Repository\Dictionary\EntryRepository;
use App\Dictionary\JMDict\DataMapper\EntryDataMapper;
use App\Dictionary\JMDict\Dto\Entry;
use App\Dictionary\JMDict\Dto\Sense;
use App\Dictionary\JMDict\Dto\Translation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

final readonly class JMDictImporter
{
    private const int BATCH_SIZE = 1000;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntryDataMapper $entryDataMapper,
        private EntryRepository $entryRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function import(string $filename): void
    {
        $parser = new JMDictParser($filename);

        $currentBatchEntriesCount = 1;
        $batchesCount = 0;

        $stopwatch = new Stopwatch();
        $stopwatch->start('importBatch');

        while (($entry = $parser->next()) instanceof Entry) {
            $entryEntity = $this->entryRepository->findOneBy([
                'sequenceId' => $entry->sequenceId,
            ]);

            if (!$entryEntity instanceof EntryEntity) {
                $entryEntity = new EntryEntity();
            }

            $this->entryDataMapper->mapDtoToEntity($entry, $entryEntity);

            $this->entityManager->persist($entryEntity);

            if ($currentBatchEntriesCount >= self::BATCH_SIZE) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                $stopwatchEvent = $stopwatch->stop('importBatch');

                $currentBatchEntriesCount = 1;
                ++$batchesCount;

                $this->logger->info('Imported batch n°{batchesCount} ({entriesCount} total entries) in {duration} ms', [
                    'batchesCount' => $batchesCount,
                    'entriesCount' => $batchesCount * self::BATCH_SIZE,
                    'duration' => $stopwatchEvent->getDuration(),
                ]);

                $stopwatch->reset();
                $stopwatch->start('importBatch');

                continue;
            }

            ++$currentBatchEntriesCount;
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        $stopwatchEvent = $stopwatch->stop('importBatch');

        ++$batchesCount;

        $this->logger->info('Imported batch n°{batchesCount} ({entriesCount} total entries) in {duration} ms', [
            'batchesCount' => $batchesCount,
            'entriesCount' => $batchesCount * self::BATCH_SIZE,
            'duration' => $stopwatchEvent->getDuration(),
        ]);
    }
}
