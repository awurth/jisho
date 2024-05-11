<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\ApiResource\Deck\Deck;
use App\Entity\Deck\Deck as DeckEntity;

final readonly class DeckDataTransformer
{
    public function transformEntityToApiResource(DeckEntity $entity): Deck
    {
        $deck = new Deck();
        $deck->entity = $entity;
        $deck->id = $entity->getId();
        $deck->owner = $entity->owner;
        $deck->name = $entity->name;
        $deck->createdAt = $entity->createdAt;

        return $deck;
    }
}
