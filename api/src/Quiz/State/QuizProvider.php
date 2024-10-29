<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Quiz\QuizRepository;
use App\Common\Security\Security;
use App\Quiz\ApiResource\Quiz;
use App\Quiz\DataTransformer\QuizDataTransformer;
use Override;
use function Functional\map;

/**
 * @implements ProviderInterface<Quiz>
 */
final readonly class QuizProvider implements ProviderInterface
{
    public function __construct(
        private QuizDataTransformer $quizDataTransformer,
        private QuizRepository $quizRepository,
        private Security $security,
    ) {
    }

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $quizzes = $this->quizRepository->findByOwner($this->security->getUser());

            return map($quizzes, $this->quizDataTransformer->transformEntityToApiResource(...));
        }

        $quizEntity = $this->quizRepository->find($uriVariables['id']);

        if (!$quizEntity instanceof QuizEntity) {
            return null;
        }

        return $this->quizDataTransformer->transformEntityToApiResource($quizEntity);
    }
}
