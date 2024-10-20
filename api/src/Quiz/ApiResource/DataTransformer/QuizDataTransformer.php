<?php

declare(strict_types=1);

namespace App\Quiz\ApiResource\DataTransformer;

use App\Common\Entity\Deck\Deck as DeckEntity;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Common\Repository\Deck\DeckRepository;
use App\Deck\ApiResource\DataTransformer\DeckDataTransformer;
use App\Deck\ApiResource\Deck;
use App\Quiz\ApiResource\Quiz;
use InvalidArgumentException;
use RuntimeException;

final readonly class QuizDataTransformer
{
    public function __construct(
        private DeckDataTransformer $deckDataTransformer,
        private DeckRepository $deckRepository,
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
        if (!$resource->deck instanceof Deck) {
            throw new InvalidArgumentException('Deck should be set.');
        }

        $deckEntity = $this->deckRepository->find($resource->deck->id);
        if (!$deckEntity instanceof DeckEntity) {
            throw new RuntimeException('Deck not found.');
        }

        $entity = new QuizEntity();
        $entity->deck = $deckEntity;
        $entity->maxQuestions = $resource->maxQuestions;
        $entity->startedAt = $resource->startedAt;
        $entity->endedAt = $resource->endedAt;

        return $entity;
    }
}
