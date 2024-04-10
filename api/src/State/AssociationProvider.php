<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Association;
use App\Repository\JapaneseFrenchAssociationRepository;
use Override;
use function array_key_exists;
use function array_values;

final readonly class AssociationProvider implements ProviderInterface
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

        $associations = [];
        foreach ($entities as $entity) {
            if (!array_key_exists($entity->japanese->value, $associations)) {
                $associations[$entity->japanese->value] = new Association();
                $associations[$entity->japanese->value]->id = $entity->japanese->getId();
                $associations[$entity->japanese->value]->dictionary = $entity->japanese->dictionary;
                $associations[$entity->japanese->value]->japanese = $entity->japanese->value;
            }

            $associations[$entity->japanese->value]->french[] = $entity->french->value;
        }

        return array_values($associations);
    }

    private function provideItem(Operation $operation, array $uriVariables, array $context): ?Association
    {
        $entities = $this->japaneseFrenchAssociationRepository->findByJapanese($uriVariables['id']);

        $association = new Association();
        foreach ($entities as $entity) {
            $association->id = $entity->japanese->getId();
            $association->dictionary = $entity->japanese->dictionary;
            $association->japanese = $entity->japanese->value;
            $association->french[] = $entity->french->value;
        }

        return $association;
    }
}
