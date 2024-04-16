<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Entry;
use App\Entity\FrenchEntry;
use App\Entity\JapaneseEntry;
use App\Entity\JapaneseFrenchAssociation;
use App\Repository\FrenchEntryRepository;
use App\Repository\JapaneseEntryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use function array_diff;
use function array_map;
use function in_array;

/**
 * @implements ProcessorInterface<Entry, Entry>
 */
final readonly class EntryProcessor implements ProcessorInterface
{
    public function __construct(
        private JapaneseEntryRepository $japaneseEntryRepository,
        private FrenchEntryRepository $frenchEntryRepository,
        private TagRepository $tagRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Entry
    {
        $japaneseEntry = $this->japaneseEntryRepository->findOneBy(['value' => $data->japanese]);

        if (!$japaneseEntry instanceof JapaneseEntry) {
            $japaneseEntry = new JapaneseEntry($data->dictionary, $data->japanese);

            $this->entityManager->persist($japaneseEntry);
        }

        foreach ($japaneseEntry->associations as $association) {
            if (!in_array($association->frenchEntry->value, $data->french, true)) {
                $japaneseEntry->associations->removeElement($association);
                $this->entityManager->remove($association);
            }
        }

        $existingFrenchEntries = $this->frenchEntryRepository->findBy(['value' => $data->french]);
        $existingFrenchEntriesValues = array_map(static fn (FrenchEntry $frenchEntry): string => $frenchEntry->value, $existingFrenchEntries);

        $frenchEntriesToCreate = array_diff($data->french, $existingFrenchEntriesValues);

        foreach ($frenchEntriesToCreate as $french) {
            $frenchEntry = new FrenchEntry($data->dictionary, $french);
            $association = new JapaneseFrenchAssociation($japaneseEntry, $frenchEntry);
            $japaneseEntry->associations->add($association);

            $this->entityManager->persist($frenchEntry);
            $this->entityManager->persist($association);
        }

        $this->entityManager->flush();

        $data->id = $japaneseEntry->getId();

        return $data;
    }

    private function mergeAssociations(): void
    {

    }
}
