<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Quiz\Question as QuestionEntity;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Deck\CardRepository;
use App\Common\Repository\Quiz\QuestionRepository;
use App\Common\Repository\Quiz\QuizRepository;
use App\Quiz\ApiResource\DataTransformer\QuestionDataTransformer;
use App\Quiz\ApiResource\Question;
use App\Quiz\ApiResource\Quiz;
use App\Quiz\Exception\QuizEndedException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Override;
use RuntimeException;
use Symfony\Component\Uid\Uuid;
use function Functional\map;

/**
 * @implements ProcessorInterface<Question, Question>
 */
final readonly class QuestionCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private CardRepository $cardRepository,
        private EntityManagerInterface $entityManager,
        private QuestionDataTransformer $questionDataTransformer,
        private QuizRepository $quizRepository,
        private QuestionRepository $questionRepository,
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

        $lastQuestionEntity = $this->questionRepository->findOneBy(
            ['quiz' => $quizEntity],
            ['createdAt' => 'DESC'],
        );

        if ($lastQuestionEntity instanceof QuestionEntity && !$lastQuestionEntity->answeredAt instanceof DateTimeImmutable) {
            return $this->questionDataTransformer->transformEntityToApiResource($lastQuestionEntity);
        }

        $quizQuestions = $this->questionRepository->findBy(['quiz' => $quizEntity]);
        $quizCardsIds = map($quizQuestions, static fn (QuestionEntity $questionEntity): Uuid => $questionEntity->card->getId());

        $question = new QuestionEntity();
        $question->quiz = $quizEntity;
        $question->card = $this->cardRepository->getRandomCard($quizEntity->deck->getId(), ...$quizCardsIds);

        $quizEntity->startedAt = new DateTimeImmutable();

        $this->entityManager->persist($question);
        $this->entityManager->persist($quizEntity);
        $this->entityManager->flush();

        return $this->questionDataTransformer->transformEntityToApiResource($question);
    }
}
