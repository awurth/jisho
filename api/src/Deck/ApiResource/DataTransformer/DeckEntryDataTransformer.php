<?php

declare(strict_types=1);

namespace App\Deck\ApiResource\DataTransformer;

use App\Common\Entity\Deck\DeckEntry as DeckEntryEntity;
use App\Deck\ApiResource\DeckEntry;
use App\Dictionary\ApiResource\DataTransformer\EntryDataTransformer;

final readonly class DeckEntryDataTransformer
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private EntryDataTransformer $entryDataTransformer,
    ) {
    }

    public function transformEntityToApiResource(DeckEntryEntity $entity): DeckEntry
    {
        $deckEntry = new DeckEntry();
        $deckEntry->entity = $entity;
        $deckEntry->id = $entity->getId();
        $deckEntry->deck = $this->deckDataTransformer->transformEntityToApiResource($entity->deck);
        $deckEntry->entry = $this->entryDataTransformer->transformEntityToApiResource($entity->entry);
        $deckEntry->addedAt = $entity->addedAt;

        return $deckEntry;
    }

    public function transformApiResourceToEntity(DeckEntry $resource): DeckEntryEntity
    {
        $entity = new DeckEntryEntity();
        $entity->deck = $resource->deck->entity;
        $entity->entry = $resource->entry->entity;

        return $entity;
    }
}
