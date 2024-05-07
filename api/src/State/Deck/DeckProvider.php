<?php

declare(strict_types=1);

namespace App\State\Deck;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Deck\Deck;
use App\Entity\Deck as DeckEntity;
use App\Repository\DeckRepository;

final readonly class DeckProvider implements ProviderInterface
{
    public function __construct(
        private DeckRepository $deckRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return [];
        }

        $deckEntity = $this->deckRepository->find($uriVariables['id']);

        if (!$deckEntity instanceof DeckEntity) {
            return null;
        }

        $deck = new Deck();
        $deck->entity = $deckEntity;
        $deck->id = $deckEntity->getId();
        $deck->owner = $deckEntity->owner;
        $deck->name = $deckEntity->name;

        return $deck;
    }
}
