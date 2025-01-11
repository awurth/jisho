<?php

declare(strict_types=1);

namespace App\Deck\DataTransformer;

use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Deck\ApiResource\Deck;

final readonly class DeckDataTransformer
{
    public function transformEntityToApiResource(DeckEntity $entity): Deck
    {
        $deck = new Deck();
        $deck->id = $entity->id;
        $deck->owner = $entity->owner;
        $deck->name = $entity->name;
        $deck->createdAt = $entity->createdAt;

        return $deck;
    }

    public function transformApiResourceToEntity(Deck $resource): DeckEntity
    {
        $entity = new DeckEntity();
        $entity->owner = $resource->owner;
        $entity->name = $resource->name;

        return $entity;
    }
}
