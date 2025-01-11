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
        $question = new Question();
        $question->id = $entity->id;
        $question->quiz = $this->quizDataTransformer->transformEntityToApiResource($entity->quiz);
        $question->card = $this->cardDataTransformer->transformEntityToApiResource($entity->card);
        $question->createdAt = $entity->createdAt;
        $question->answeredAt = $entity->answeredAt;
        $question->answer = $entity->answer;

        return $question;
    }
}
