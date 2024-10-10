<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProviderInterface;
use App\Common\Entity\Deck\Card as CardEntity;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Repository\Deck\CardRepository;
use App\Common\Repository\Deck\DeckRepository;
use App\Common\Security\Security;
use App\Deck\ApiResource\Card;
use App\Deck\ApiResource\DataTransformer\CardDataTransformer;
use App\Deck\ApiResource\DataTransformer\DeckDataTransformer;
use Override;
use function Functional\map;

/**
 * @implements ProviderInterface<Card>
 */
final readonly class CardProvider implements ProviderInterface
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private CardDataTransformer $cardDataTransformer,
        private CardRepository $cardRepository,
        private DeckRepository $deckRepository,
        private Security $security,
    ) {
    }

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $deckEntity = $this->deckRepository->findOneBy([
            'id' => $uriVariables['deckId'],
            'owner' => $this->security->getUser(),
        ]);

        if (!$deckEntity instanceof DeckEntity) {
            return null;
        }

        if ($operation instanceof Post) {
            $card = new Card();
            $card->deck = $this->deckDataTransformer->transformEntityToApiResource($deckEntity);

            return $card;
        }

        if ($operation instanceof CollectionOperationInterface) {
            $cards = $this->cardRepository->findBy(['deck' => $deckEntity]);

            return map($cards, $this->cardDataTransformer->transformEntityToApiResource(...));
        }

        $cardEntity = $this->cardRepository->find($uriVariables['id']);

        if (!$cardEntity instanceof CardEntity) {
            return null;
        }

        return $this->cardDataTransformer->transformEntityToApiResource($cardEntity);
    }
}
