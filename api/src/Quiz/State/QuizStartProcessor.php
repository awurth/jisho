<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Quiz\ApiResource\DataTransformer\QuizDataTransformer;
use App\Quiz\ApiResource\Quiz;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Override;

/**
 * @implements ProcessorInterface<Quiz, Quiz>
 */
final readonly class QuizStartProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuizDataTransformer $quizDataTransformer,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Quiz
    {
        $data->entity->startedAt = new DateTimeImmutable();

        $this->entityManager->persist($data->entity);
        $this->entityManager->flush();

        return $this->quizDataTransformer->transformEntityToApiResource($data->entity);
    }
}
