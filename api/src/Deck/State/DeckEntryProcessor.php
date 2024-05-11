<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Deck\ApiResource\DataTransformer\DeckEntryDataTransformer;
use App\Deck\ApiResource\DeckEntry;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Override;

/**
 * @implements ProcessorInterface<DeckEntry, DeckEntry>
 */
final readonly class DeckEntryProcessor implements ProcessorInterface
{
    public function __construct(
        private DeckEntryDataTransformer $deckEntryDataTransformer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof DeleteOperationInterface) {
            $this->entityManager->remove($data->entity);
            $this->entityManager->flush();

            return $data;
        }

        // if ($operation instanceof Patch) {
        //     $data->entity->name = $data->name;
        //
        //     $this->entityManager->persist($data->entity);
        //     $this->entityManager->flush();
        //
        //     return $data;
        // }

        if ($operation instanceof Post) {
            $entity = $this->deckEntryDataTransformer->transformApiResourceToEntity($data);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->deckEntryDataTransformer->transformEntityToApiResource($entity);
        }

        throw new LogicException('Unexpected operation');
    }
}
