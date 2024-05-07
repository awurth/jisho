<?php

declare(strict_types=1);

namespace App\State\Deck;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Deck\Deck;
use App\Entity\Deck\Deck as DeckEntity;
use App\Repository\DeckRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function Functional\map;

final readonly class DeckProvider implements ProviderInterface
{
    public function __construct(
        private DeckRepository $deckRepository,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $decks = $this->deckRepository->findBy(['owner' => $this->tokenStorage->getToken()?->getUser()]);

            return map($decks, static function (DeckEntity $entity): Deck {
                $deck = new Deck();
                $deck->id = $entity->getId();
                $deck->owner = $entity->owner;
                $deck->name = $entity->name;

                return $deck;
            });
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
