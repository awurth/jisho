<?php

declare(strict_types=1);

namespace App\Deck\DataTransformer;

use App\Common\Entity\Deck\Card as CardEntity;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Entity\Dictionary\Entry as EntryEntity;
use App\Common\Repository\Deck\DeckRepository;
use App\Common\Repository\Dictionary\EntryRepository;
use App\Deck\ApiResource\Card;
use App\Dictionary\DataTransformer\EntryDataTransformer;
use RuntimeException;

final readonly class CardDataTransformer
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private DeckRepository $deckRepository,
        private EntryDataTransformer $entryDataTransformer,
        private EntryRepository $entryRepository,
    ) {
    }

    public function transformEntityToApiResource(CardEntity $entity): Card
    {
        $card = new Card(deck: $this->deckDataTransformer->transformEntityToApiResource($entity->deck));
        $card->id = $entity->id;
        $card->entry = $this->entryDataTransformer->transformEntityToApiResource($entity->entry);
        $card->addedAt = $entity->addedAt;

        return $card;
    }

    public function transformApiResourceToEntity(Card $resource): CardEntity
    {
        $deckEntity = $this->deckRepository->find($resource->deck->id);
        if (!$deckEntity instanceof DeckEntity) {
            throw new RuntimeException('Deck not found.');
        }

        $entryEntity = $this->entryRepository->find($resource->entry->id);
        if (!$entryEntity instanceof EntryEntity) {
            throw new RuntimeException('Entry not found.');
        }

        $entity = new CardEntity();
        $entity->deck = $deckEntity;
        $entity->entry = $entryEntity;

        return $entity;
    }
}
