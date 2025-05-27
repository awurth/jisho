<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Deck\ApiResource\Card;
use App\Deck\DataTransformer\CardDataTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Override;

/**
 * @implements ProcessorInterface<Card, Card>
 */
final readonly class PostCardProcessor implements ProcessorInterface
{
    public function __construct(
        private CardDataTransformer $cardDataTransformer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Card
    {
        $entity = $this->cardDataTransformer->transformApiResourceToEntity($data);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->cardDataTransformer->transformEntityToApiResource($entity);
    }
}
