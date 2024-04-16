<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Entry;
use App\Repository\DictionaryRepository;
use App\Repository\JapaneseFrenchAssociationRepository;
use Override;
use function array_key_exists;
use function array_values;

final readonly class EntryProvider implements ProviderInterface
{
    public function __construct(
        private DictionaryRepository $dictionaryRepository,
        private JapaneseFrenchAssociationRepository $japaneseFrenchAssociationRepository,
    ) {
    }

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->provideCollection($operation, $uriVariables, $context);
        }

        return $this->provideItem($operation, $uriVariables, $context);
    }

    private function provideCollection(Operation $operation, array $uriVariables, array $context): array
    {
        $queryBuilder = $this->japaneseFrenchAssociationRepository->findByDictionaryQueryBuilder($uriVariables['dictionaryId']);

        $entities = $queryBuilder->getQuery()->getResult();

        $entries = [];
        foreach ($entities as $entity) {
            if (!array_key_exists($entity->japaneseEntry->value, $entries)) {
                $entries[$entity->japaneseEntry->value] = new Entry();
                $entries[$entity->japaneseEntry->value]->id = $entity->japaneseEntry->getId();
                $entries[$entity->japaneseEntry->value]->dictionary = $entity->japaneseEntry->dictionary;
                $entries[$entity->japaneseEntry->value]->japanese = $entity->japaneseEntry->value;
            }

            $entries[$entity->japaneseEntry->value]->french[] = $entity->frenchEntry->value;
        }

        return array_values($entries);
    }

    private function provideItem(Operation $operation, array $uriVariables, array $context): ?Entry
    {
        if ($operation instanceof Post) {
            $dictionary = $this->dictionaryRepository->find($uriVariables['dictionaryId']);

            $entry = new Entry();
            $entry->dictionary = $dictionary;

            return $entry;
        }

        $entities = $this->japaneseFrenchAssociationRepository->findByJapanese($uriVariables['id']);

        $entry = new Entry();
        foreach ($entities as $entity) {
            $entry->id = $entity->japaneseEntry->getId();
            $entry->dictionary = $entity->japaneseEntry->dictionary;
            $entry->japanese = $entity->japaneseEntry->value;
            $entry->french[] = $entity->frenchEntry->value;
        }

        return $entry;
    }
}
