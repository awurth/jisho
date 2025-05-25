<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProviderInterface;
use App\Common\Entity\Quiz\Question as QuestionEntity;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Quiz\QuestionRepository;
use App\Common\Repository\Quiz\QuizRepository;
use App\Quiz\ApiResource\Question;
use App\Quiz\DataTransformer\QuestionDataTransformer;
use App\Quiz\DataTransformer\QuizDataTransformer;
use Override;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function Functional\map;

/**
 * @implements ProviderInterface<Question>
 */
final readonly class QuestionProvider implements ProviderInterface
{
    public function __construct(
        private QuestionDataTransformer $questionDataTransformer,
        private QuestionRepository $questionRepository,
        private QuizDataTransformer $quizDataTransformer,
        private QuizRepository $quizRepository,
    ) {
    }

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $quizEntity = $this->quizRepository->find($uriVariables['quizId']);

        if (!$quizEntity instanceof QuizEntity) {
            throw new NotFoundHttpException();
        }

        if ($operation instanceof Post) {
            $question = new Question();
            $question->quiz = $this->quizDataTransformer->transformEntityToApiResource($quizEntity);

            return $question;
        }

        if ($operation instanceof CollectionOperationInterface) {
            $questions = $this->questionRepository->findBy(['quiz' => $quizEntity]);

            return map($questions, $this->questionDataTransformer->transformEntityToApiResource(...));
        }

        $questionEntity = $this->questionRepository->find($uriVariables['id']);

        if (!$questionEntity instanceof QuestionEntity) {
            return null;
        }

        return $this->questionDataTransformer->transformEntityToApiResource($questionEntity);
    }
}
