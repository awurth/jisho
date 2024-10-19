<?php

declare(strict_types=1);

namespace App\Deck\ApiResource\DataTransformer;

use App\Common\Entity\Deck\Card as CardEntity;
use App\Deck\ApiResource\Card;
use App\Deck\ApiResource\Deck;
use App\Dictionary\ApiResource\DataTransformer\EntryDataTransformer;
use LogicException;

final readonly class CardDataTransformer
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private EntryDataTransformer $entryDataTransformer,
    ) {
    }

    public function transformEntityToApiResource(CardEntity $entity): Card
    {
        $card = new Card();
        $card->entity = $entity;
        $card->id = $entity->getId();
        $card->deck = $this->deckDataTransformer->transformEntityToApiResource($entity->deck);
        $card->entry = $this->entryDataTransformer->transformEntityToApiResource($entity->entry);
        $card->addedAt = $entity->addedAt;

        return $card;
    }

    public function transformApiResourceToEntity(Card $resource): CardEntity
    {
        if (!$resource->deck instanceof Deck) {
            throw new LogicException('Deck should be set.');
        }

        $entity = new CardEntity();
        $entity->deck = $resource->deck->entity;
        $entity->entry = $resource->entry->entity;

        return $entity;
    }
}
