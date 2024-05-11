<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Repository\Deck\DeckRepository;
use App\Deck\ApiResource\DataTransformer\DeckDataTransformer;
use App\Deck\ApiResource\Deck;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function Functional\map;

/**
 * @implements ProviderInterface<Deck>
 */
final readonly class DeckProvider implements ProviderInterface
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private DeckRepository $deckRepository,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $decks = $this->deckRepository->findBy(['owner' => $this->tokenStorage->getToken()?->getUser()]);

            return map($decks, $this->deckDataTransformer->transformEntityToApiResource(...));
        }

        $deckEntity = $this->deckRepository->find($uriVariables['id']);

        if (!$deckEntity instanceof DeckEntity) {
            return null;
        }

        return $this->deckDataTransformer->transformEntityToApiResource($deckEntity);
    }
}
