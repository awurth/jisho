<?php

declare(strict_types=1);

namespace App\State\Dictionary;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DataTransformer\EntryDataTransformer;
use App\Entity\Dictionary\Entry as EntryEntity;
use App\Repository\Dictionary\EntryRepository;

final readonly class EntryProvider implements ProviderInterface
{
    public function __construct(
        private EntryDataTransformer $entryDataTransformer,
        private EntryRepository $entryRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $entryEntity = $this->entryRepository->find($uriVariables['id']);

        if (!$entryEntity instanceof EntryEntity) {
            return null;
        }

        return $this->entryDataTransformer->transformEntityToApiResource($entryEntity);
    }
}
