<?php

declare(strict_types=1);

namespace App\State\Deck;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Deck\Deck;
use App\Entity\Deck\Deck as DeckEntity;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @implements ProcessorInterface<Deck|DeckEntity>
 */
final readonly class DeckProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

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
            $user = $this->tokenStorage->getToken()?->getUser();

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
