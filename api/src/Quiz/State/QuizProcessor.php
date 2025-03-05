<?php

declare(strict_types=1);

namespace App\Quiz\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Deck\DeckRepository;
use App\Common\Repository\Quiz\QuizRepository;
use App\Deck\ApiResource\Deck;
use App\Quiz\ApiResource\Quiz;
use App\Quiz\DataTransformer\QuizDataTransformer;
use App\Quiz\Factory\QuizFactory;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use LogicException;
use Override;
use RuntimeException;

/**
 * @implements ProcessorInterface<Quiz, Quiz>
 */
final readonly class QuizProcessor implements ProcessorInterface
{
    public function __construct(
        private DeckRepository $deckRepository,
        private EntityManagerInterface $entityManager,
        private QuizDataTransformer $quizDataTransformer,
        private QuizFactory $quizFactory,
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
            if (!$data->deck instanceof Deck) {
                throw new InvalidArgumentException('Deck should be set.');
            }

            $deckEntity = $this->deckRepository->find($data->deck->id);
            if (!$deckEntity instanceof DeckEntity) {
                throw new RuntimeException('Deck not found.');
            }

            $quizEntity = $this->quizFactory->create(deck: $deckEntity, maxQuestions: $data->maxQuestions);

            $this->entityManager->persist($quizEntity);
            $this->entityManager->flush();

            return $this->quizDataTransformer->transformEntityToApiResource($quizEntity);
        }

        throw new LogicException('Unexpected operation');
    }
}
