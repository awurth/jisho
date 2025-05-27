<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Deck\Card as CardEntity;
use App\Common\Repository\Deck\CardRepository;
use App\Deck\ApiResource\Card;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use RuntimeException;

/**
 * @implements ProcessorInterface<Card, Card>
 */
final readonly class DeleteCardProcessor implements ProcessorInterface
{
    public function __construct(
        private CardRepository $cardRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Card
    {
        $cardEntity = $this->cardRepository->find($data->id);
        if (!$cardEntity instanceof CardEntity) {
            throw new RuntimeException('Card not found.');
        }

        $this->entityManager->remove($cardEntity);
        $this->entityManager->flush();

        return $data;
    }
}
