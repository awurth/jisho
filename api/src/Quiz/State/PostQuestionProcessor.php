<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Quiz\Question as QuestionEntity;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Quiz\QuestionRepository;
use App\Common\Repository\Quiz\QuizRepository;
use App\Quiz\ApiResource\Question;
use App\Quiz\ApiResource\Quiz;
use App\Quiz\DataTransformer\QuestionDataTransformer;
use App\Quiz\Exception\QuizEndedException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use LogicException;
use Override;
use RuntimeException;

/**
 * @implements ProcessorInterface<Question, Question>
 */
final readonly class PostQuestionProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuestionDataTransformer $questionDataTransformer,
        private QuestionRepository $questionRepository,
        private QuizRepository $quizRepository,
    ) {
    }

    #[Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Question
    {
        if (!$data->quiz instanceof Quiz) {
            throw new InvalidArgumentException('Quiz should be set.');
        }

        $quizEntity = $this->quizRepository->find($data->quiz->id);
        if (!$quizEntity instanceof QuizEntity) {
            throw new RuntimeException('Quiz not found.');
        }

        if ($quizEntity->endedAt instanceof DateTimeImmutable) {
            throw new QuizEndedException();
        }

        $lastUnansweredQuestionEntity = $this->questionRepository->findLastUnansweredQuestion(quizId: $quizEntity->id);

        if (!$lastUnansweredQuestionEntity instanceof QuestionEntity) {
            throw new LogicException('The quiz is not marked as ended but no unanswered question was found.');
        }

        if (!$quizEntity->startedAt instanceof DateTimeImmutable) {
            $quizEntity->startedAt = new DateTimeImmutable();

            $this->entityManager->persist($quizEntity);
            $this->entityManager->flush();
        }

        return $this->questionDataTransformer->transformEntityToApiResource($lastUnansweredQuestionEntity);
    }
}
