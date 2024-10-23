<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Deck\Card as CardEntity;
use App\Common\Repository\Deck\CardRepository;
use App\Deck\ApiResource\Card;
use App\Deck\ApiResource\DataTransformer\CardDataTransformer;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Override;
use RuntimeException;

/**
 * @implements ProcessorInterface<Card, Card>
 */
final readonly class CardProcessor implements ProcessorInterface
{
    public function __construct(
        private CardDataTransformer $cardDataTransformer,
        private CardRepository $cardRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Card
    {
        if ($operation instanceof DeleteOperationInterface) {
            $cardEntity = $this->cardRepository->find($data->id);
            if (!$cardEntity instanceof CardEntity) {
                throw new RuntimeException('Card not found.');
            }

            $this->entityManager->remove($cardEntity);
            $this->entityManager->flush();

            return $data;
        }

        if ($operation instanceof Post) {
            $entity = $this->cardDataTransformer->transformApiResourceToEntity($data);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->cardDataTransformer->transformEntityToApiResource($entity);
        }

        throw new LogicException('Unexpected operation');
    }
}
