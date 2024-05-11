<?php

declare(strict_types=1);

namespace App\Dictionary\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Common\Entity\Dictionary\Entry as EntryEntity;
use App\Common\Repository\Dictionary\EntryRepository;
use App\Dictionary\ApiResource\DataTransformer\EntryDataTransformer;
use App\Dictionary\ApiResource\Entry;
use Override;

/**
 * @implements ProviderInterface<Entry>
 */
final readonly class EntryProvider implements ProviderInterface
{
    public function __construct(
        private EntryDataTransformer $entryDataTransformer,
        private EntryRepository $entryRepository,
    ) {
    }

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $entryEntity = $this->entryRepository->find($uriVariables['id']);

        if (!$entryEntity instanceof EntryEntity) {
            return null;
        }

        return $this->entryDataTransformer->transformEntityToApiResource($entryEntity);
    }
}
