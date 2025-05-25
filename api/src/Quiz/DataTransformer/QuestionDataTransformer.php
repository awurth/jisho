<?php

declare(strict_types=1);

namespace App\Quiz\DataTransformer;

use App\Common\Entity\Quiz\Question as QuestionEntity;
use App\Deck\DataTransformer\CardDataTransformer;
use App\Quiz\ApiResource\Question;

final readonly class QuestionDataTransformer
{
    public function __construct(
        private CardDataTransformer $cardDataTransformer,
        private QuizDataTransformer $quizDataTransformer,
    ) {
    }

    public function transformEntityToApiResource(QuestionEntity $entity): Question
    {
        $question = new Question(quiz: $this->quizDataTransformer->transformEntityToApiResource($entity->quiz));
        $question->id = $entity->id;
        $question->card = $this->cardDataTransformer->transformEntityToApiResource($entity->card);
        $question->position = $entity->position;
        $question->answeredAt = $entity->answeredAt;
        $question->skippedAt = $entity->skippedAt;
        $question->answer = $entity->answer;

        return $question;
    }
}
