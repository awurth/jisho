<?php

declare(strict_types=1);

namespace App\Quiz\ApiResource\DataTransformer;

use App\Common\Entity\Deck\Card as CardEntity;
use App\Common\Entity\Quiz\Question as QuestionEntity;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Deck\CardRepository;
use App\Common\Repository\Quiz\QuizRepository;
use App\Deck\ApiResource\DataTransformer\CardDataTransformer;
use App\Quiz\ApiResource\Question;
use App\Quiz\ApiResource\Quiz;
use InvalidArgumentException;
use RuntimeException;

final readonly class QuestionDataTransformer
{
    public function __construct(
        private CardDataTransformer $cardDataTransformer,
        // private CardRepository $cardRepository,
        private QuizDataTransformer $quizDataTransformer,
        // private QuizRepository $quizRepository,
    ) {
    }

    public function transformEntityToApiResource(QuestionEntity $entity): Question
    {
        $question = new Question();
        $question->id = $entity->getId();
        $question->quiz = $this->quizDataTransformer->transformEntityToApiResource($entity->quiz);
        $question->card = $this->cardDataTransformer->transformEntityToApiResource($entity->card);
        $question->createdAt = $entity->createdAt;
        $question->answeredAt = $entity->answeredAt;
        $question->answer = $entity->answer;

        return $question;
    }

    // public function transformApiResourceToEntity(Question $resource): QuestionEntity
    // {
    //     if (!$resource->quiz instanceof Quiz) {
    //         throw new InvalidArgumentException('Quiz should be set.');
    //     }
    //
    //     $quizEntity = $this->quizRepository->find($resource->quiz->id);
    //     if (!$quizEntity instanceof QuizEntity) {
    //         throw new RuntimeException('Quiz not found.');
    //     }
    //
    //     $cardEntity = $this->cardRepository->find($resource->card->id);
    //     if (!$cardEntity instanceof CardEntity) {
    //         throw new RuntimeException('Card not found.');
    //     }
    //
    //     $entity = new QuestionEntity();
    //     $entity->quiz = $quizEntity;
    //     $entity->card = $cardEntity;
    //     $entity->createdAt = $resource->createdAt;
    //
    //     return $entity;
    // }
}
