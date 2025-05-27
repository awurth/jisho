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
use App\Deck\ApiResource\Card;
use App\Deck\DataTransformer\CardDataTransformer;
use App\Deck\DataTransformer\DeckDataTransformer;
use Override;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    ) {
    }

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $deckEntity = $this->deckRepository->find($uriVariables['deckId']);
        if (!$deckEntity instanceof DeckEntity) {
            throw new NotFoundHttpException('Deck not found.');
        }

        if ($operation instanceof Post) {
            return new Card(deck: $this->deckDataTransformer->transformEntityToApiResource($deckEntity));
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
