<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict;

use App\Dictionary\JMDict\DataTransformer\EntryDataTransformer;
use App\Dictionary\JMDict\Dto\Entry;
use Doctrine\ORM\EntityManagerInterface;

final readonly class JMDictImporter
{
    private const int BATCH_SIZE = 1000;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntryDataTransformer $entryDataTransformer,
    ) {
    }

    public function import(string $filename): void
    {
        $parser = new JMDictParser($filename);

        $counter = 0;

        while (($entry = $parser->next()) instanceof Entry) {
            $entryEntity = $this->entryDataTransformer->transformToEntity($entry);

            $this->entityManager->persist($entryEntity);

            if ($counter > self::BATCH_SIZE) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                $counter = 0;

                continue;
            }

            ++$counter;
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
