<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Repository\Deck\DeckRepository;
use App\Deck\ApiResource\DataTransformer\DeckDataTransformer;
use App\Deck\ApiResource\Deck;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Override;
use RuntimeException;

/**
 * @implements ProcessorInterface<Deck, Deck>
 */
final readonly class DeckProcessor implements ProcessorInterface
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private DeckRepository $deckRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Deck
    {
        if ($operation instanceof DeleteOperationInterface) {
            $deckEntity = $this->deckRepository->find($data->id);
            if (!$deckEntity instanceof DeckEntity) {
                throw new RuntimeException('Deck not found.');
            }

            $this->entityManager->remove($deckEntity);
            $this->entityManager->flush();

            return $data;
        }

        if ($operation instanceof Patch) {
            $deckEntity = $this->deckRepository->find($data->id);
            if (!$deckEntity instanceof DeckEntity) {
                throw new RuntimeException('Deck not found.');
            }

            $deckEntity->name = $data->name;

            $this->entityManager->persist($deckEntity);
            $this->entityManager->flush();

            return $data;
        }

        if ($operation instanceof Post) {
            $entity = $this->deckDataTransformer->transformApiResourceToEntity($data);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->deckDataTransformer->transformEntityToApiResource($entity);
        }

        throw new LogicException('Unexpected operation');
    }
}
