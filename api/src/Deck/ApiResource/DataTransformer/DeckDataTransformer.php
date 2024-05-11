<?php

declare(strict_types=1);

namespace App\Deck\ApiResource\DataTransformer;

use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Deck\ApiResource\Deck;

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
