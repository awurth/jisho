<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Quiz\QuizRepository;
use App\Quiz\ApiResource\DataTransformer\QuizDataTransformer;
use App\Quiz\ApiResource\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Override;
use RuntimeException;

/**
 * @implements ProcessorInterface<Quiz, Quiz>
 */
final readonly class QuizProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuizDataTransformer $quizDataTransformer,
        private QuizRepository $quizRepository,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Quiz
    {
        if ($operation instanceof DeleteOperationInterface) {
            $quizEntity = $this->quizRepository->find($data->id);
            if (!$quizEntity instanceof QuizEntity) {
                throw new RuntimeException('Quiz not found.');
            }

            $this->entityManager->remove($quizEntity);
            $this->entityManager->flush();

            return $data;
        }

        if ($operation instanceof Post) {
            $entity = $this->quizDataTransformer->transformApiResourceToEntity($data);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->quizDataTransformer->transformEntityToApiResource($entity);
        }

        throw new LogicException('Unexpected operation');
    }
}
