<?php

declare(strict_types=1);

namespace App\State\Deck;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Deck\DeckEntry;
use App\DataTransformer\DeckDataTransformer;
use App\DataTransformer\DeckEntryDataTransformer;
use App\Entity\Deck\Deck as DeckEntity;
use App\Entity\Deck\DeckEntry as DeckEntryEntity;
use App\Repository\Deck\DeckEntryRepository;
use App\Repository\Deck\DeckRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function Functional\map;

/**
 * @implements ProviderInterface<DeckEntry>
 */
final readonly class DeckEntryProvider implements ProviderInterface
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private DeckEntryDataTransformer $deckEntryDataTransformer,
        private DeckEntryRepository $deckEntryRepository,
        private DeckRepository $deckRepository,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $deckEntity = $this->deckRepository->findOneBy([
            'id' => $uriVariables['deckId'],
            'owner' => $this->tokenStorage->getToken()?->getUser(),
        ]);

        if (!$deckEntity instanceof DeckEntity) {
            return null;
        }

        if ($operation instanceof Post) {
            $deckEntry = new DeckEntry();
            $deckEntry->deck = $this->deckDataTransformer->transformEntityToApiResource($deckEntity);

            return $deckEntry;
        }

        if ($operation instanceof CollectionOperationInterface) {
            $deckEntries = $this->deckEntryRepository->findBy(['deck' => $deckEntity]);

            return map($deckEntries, $this->deckEntryDataTransformer->transformEntityToApiResource(...));
        }

        $deckEntryEntity = $this->deckEntryRepository->find($uriVariables['id']);

        if (!$deckEntryEntity instanceof DeckEntryEntity) {
            return null;
        }

        return $this->deckEntryDataTransformer->transformEntityToApiResource($deckEntryEntity);
    }
}
