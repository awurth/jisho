<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProviderInterface;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Entity\Deck\DeckEntry as DeckEntryEntity;
use App\Common\Repository\Deck\DeckEntryRepository;
use App\Common\Repository\Deck\DeckRepository;
use App\Deck\ApiResource\DataTransformer\DeckDataTransformer;
use App\Deck\ApiResource\DataTransformer\DeckEntryDataTransformer;
use App\Deck\ApiResource\DeckEntry;
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
