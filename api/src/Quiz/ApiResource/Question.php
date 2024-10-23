<?php

declare(strict_types=1);

namespace App\Quiz\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Deck\ApiResource\Card;
use App\Quiz\Exception\QuizEndedException;
use App\Quiz\State\QuestionCreateProcessor;
use App\Quiz\State\QuestionProvider;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/quizzes/{quizId}/questions',
            uriVariables: [
                'quizId' => new Link(fromClass: Quiz::class),
            ],
            normalizationContext: [
                'groups' => ['question:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("ROLE_USER")',
            provider: QuestionProvider::class,
        ),
        new Get(
            uriTemplate: '/quizzes/{quizId}/questions/{id}',
            uriVariables: [
                'quizId' => new Link(fromClass: Quiz::class),
                'id' => new Link(fromClass: Question::class),
            ],
            normalizationContext: [
                'groups' => ['question:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("QUESTION_VIEW", object)',
            provider: QuestionProvider::class,
        ),
        new Post(
            uriTemplate: '/quizzes/{quizId}/questions',
            uriVariables: [
                'quizId' => new Link(fromClass: Quiz::class),
            ],
            exceptionToStatus: [QuizEndedException::class => 423],
            normalizationContext: [
                'groups' => ['question:read'],
                'openapi_definition_name' => 'Read',
            ],
            denormalizationContext: [
                'groups' => ['question:write'],
                'openapi_definition_name' => 'Write',
            ],
            securityPostDenormalize: 'is_granted("QUESTION_CREATE", object.quiz)',
            provider: QuestionProvider::class,
            processor: QuestionCreateProcessor::class,
        ),
    ],
)]
final class Question
{
    #[Groups(['question:read'])]
    public Uuid $id;

    public ?Quiz $quiz = null;

    #[Groups(['question:read'])]
    public Card $card;

    #[Groups(['question:read'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['question:read'])]
    public ?DateTimeImmutable $answeredAt = null;

    #[Groups(['question:read'])]
    public string $answer = '';
}
