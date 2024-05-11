<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Repository\Deck\DeckRepository;
use App\Common\Security\Security;
use App\Deck\ApiResource\DataTransformer\DeckDataTransformer;
use App\Deck\ApiResource\Deck;
use function Functional\map;

/**
 * @implements ProviderInterface<Deck>
 */
final readonly class DeckProvider implements ProviderInterface
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private DeckRepository $deckRepository,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $decks = $this->deckRepository->findBy(['owner' => $this->security->getUser()]);

            return map($decks, $this->deckDataTransformer->transformEntityToApiResource(...));
        }

        $deckEntity = $this->deckRepository->find($uriVariables['id']);

        if (!$deckEntity instanceof DeckEntity) {
            return null;
        }

        return $this->deckDataTransformer->transformEntityToApiResource($deckEntity);
    }
}
