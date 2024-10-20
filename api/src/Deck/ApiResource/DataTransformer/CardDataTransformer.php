<?php

declare(strict_types=1);

namespace App\Deck\ApiResource\DataTransformer;

use App\Common\Entity\Deck\Card as CardEntity;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Entity\Dictionary\Entry as EntryEntity;
use App\Common\Repository\Deck\DeckRepository;
use App\Common\Repository\Dictionary\EntryRepository;
use App\Deck\ApiResource\Card;
use App\Deck\ApiResource\Deck;
use App\Dictionary\ApiResource\DataTransformer\EntryDataTransformer;
use InvalidArgumentException;
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
        $card = new Card();
        $card->id = $entity->getId();
        $card->deck = $this->deckDataTransformer->transformEntityToApiResource($entity->deck);
        $card->entry = $this->entryDataTransformer->transformEntityToApiResource($entity->entry);
        $card->addedAt = $entity->addedAt;

        return $card;
    }

    public function transformApiResourceToEntity(Card $resource): CardEntity
    {
        if (!$resource->deck instanceof Deck) {
            throw new InvalidArgumentException('Deck should be set.');
        }

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
