<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Quiz\ApiResource\DataTransformer\QuizDataTransformer;
use App\Quiz\ApiResource\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Override;

/**
 * @implements ProcessorInterface<Quiz, Quiz>
 */
final readonly class QuizProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuizDataTransformer $quizDataTransformer,
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
            $entity = $this->quizDataTransformer->transformApiResourceToEntity($data);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->quizDataTransformer->transformEntityToApiResource($entity);
        }

        throw new LogicException('Unexpected operation');
    }
}
