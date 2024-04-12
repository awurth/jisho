<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Entry;
use App\Repository\JapaneseFrenchAssociationRepository;
use Override;
use function array_key_exists;
use function array_values;

final readonly class EntryProvider implements ProviderInterface
{
    public function __construct(
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
            if (!array_key_exists($entity->japanese->value, $entries)) {
                $entries[$entity->japanese->value] = new Entry();
                $entries[$entity->japanese->value]->id = $entity->japanese->getId();
                $entries[$entity->japanese->value]->dictionary = $entity->japanese->dictionary;
                $entries[$entity->japanese->value]->japanese = $entity->japanese->value;
            }

            $entries[$entity->japanese->value]->french[] = $entity->french->value;
        }

        return array_values($entries);
    }

    private function provideItem(Operation $operation, array $uriVariables, array $context): ?Entry
    {
        $entities = $this->japaneseFrenchAssociationRepository->findByJapanese($uriVariables['id']);

        $entry = new Entry();
        foreach ($entities as $entity) {
            $entry->id = $entity->japanese->getId();
            $entry->dictionary = $entity->japanese->dictionary;
            $entry->japanese = $entity->japanese->value;
            $entry->french[] = $entity->french->value;
        }

        return $entry;
    }
}
