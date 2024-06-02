<?php

declare(strict_types=1);

namespace App\Quiz\ApiResource\DataTransformer;

use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Deck\ApiResource\DataTransformer\DeckDataTransformer;
use App\Quiz\ApiResource\Quiz;

final readonly class QuizDataTransformer
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
    ) {
    }

    public function transformEntityToApiResource(QuizEntity $entity): Quiz
    {
        $quiz = new Quiz();
        $quiz->entity = $entity;
        $quiz->id = $entity->getId();
        $quiz->deck = $this->deckDataTransformer->transformEntityToApiResource($entity->deck);
        $quiz->maxQuestions = $entity->maxQuestions;
        $quiz->createdAt = $entity->createdAt;
        $quiz->startedAt = $entity->startedAt;
        $quiz->endedAt = $entity->endedAt;

        return $quiz;
    }

    public function transformApiResourceToEntity(Quiz $resource): QuizEntity
    {
        $entity = new QuizEntity();
        $entity->deck = $resource->deck->entity;
        $entity->maxQuestions = $resource->maxQuestions;
        $entity->startedAt = $resource->startedAt;
        $entity->endedAt = $resource->endedAt;

        return $entity;
    }
}
