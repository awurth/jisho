<?php

declare(strict_types=1);

namespace App\Deck\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Security\Security;
use App\Deck\ApiResource\Deck;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Override;

/**
 * @implements ProcessorInterface<Deck, Deck>
 */
final readonly class DeckProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
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

        if ($operation instanceof Patch) {
            $data->entity->name = $data->name;

            $this->entityManager->persist($data->entity);
            $this->entityManager->flush();

            return $data;
        }

        if ($operation instanceof Post) {
            $user = $this->security->getUser();

            $deck = new DeckEntity();
            $deck->owner = $user;
            $deck->name = $data->name;

            $this->entityManager->persist($deck);
            $this->entityManager->flush();

            $data->id = $deck->getId();
            $data->createdAt = $deck->createdAt;

            return $data;
        }

        throw new LogicException('Unexpected operation');
    }
}
