<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\ValidatorInterface;
use App\Common\Entity\Quiz\Question as QuestionEntity;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Quiz\QuizRepository;
use App\Quiz\ApiResource\Question;
use App\Quiz\ApiResource\Quiz;
use App\Quiz\DataTransformer\QuestionDataTransformer;
use App\Quiz\Exception\QuestionAlreadyAnsweredException;
use App\Quiz\Exception\QuizEndedException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Override;
use RuntimeException;
use function max;

/**
 * @implements ProcessorInterface<Question, Question>
 */
final readonly class QuestionAnswerProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuestionDataTransformer $questionDataTransformer,
        private QuizRepository $quizRepository,
        private ValidatorInterface $validator,
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

        $questionEntity = $quizEntity->questions->findFirst(static fn (int $key, QuestionEntity $question): bool => $question->id->equals($data->id));
        if (!$questionEntity instanceof QuestionEntity) {
            throw new RuntimeException('Question not found.');
        }

        if ($quizEntity->endedAt instanceof DateTimeImmutable) {
            throw new QuizEndedException();
        }

        if ($questionEntity->answeredAt instanceof DateTimeImmutable) {
            throw new QuestionAlreadyAnsweredException();
        }

        if (!$quizEntity->startedAt instanceof DateTimeImmutable) {
            $quizEntity->startedAt = new DateTimeImmutable();

            $this->entityManager->persist($quizEntity);
            $this->entityManager->flush();
        }

        $this->validator->validate($data, [
            'groups' => ['answer'],
        ]);

        $questionEntity->answeredAt = new DateTimeImmutable();
        $questionEntity->answer = $data->answer;

        /** @var non-empty-array<int, int> $questionPositions */
        $questionPositions = $quizEntity->questions->map(static fn (QuestionEntity $question): int => $question->position)->toArray();

        $lastQuestionPosition = max($questionPositions);

        if ($questionEntity->position === $lastQuestionPosition) {
            $quizEntity->endedAt = $questionEntity->answeredAt;
        }

        $this->entityManager->persist($quizEntity);
        $this->entityManager->persist($questionEntity);
        $this->entityManager->flush();

        return $this->questionDataTransformer->transformEntityToApiResource($questionEntity);
    }
}
