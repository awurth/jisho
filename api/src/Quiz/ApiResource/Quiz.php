<?php

declare(strict_types=1);

namespace App\Quiz\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Deck\ApiResource\Deck;
use App\Quiz\State\QuizProcessor;
use App\Quiz\State\QuizProvider;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Range;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/quizzes',
            normalizationContext: [
                'groups' => ['quiz:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("ROLE_USER")',
            provider: QuizProvider::class,
        ),
        new Get(
            uriTemplate: '/quizzes/{id}',
            uriVariables: [
                'id' => new Link(fromClass: Quiz::class),
            ],
            normalizationContext: [
                'groups' => ['quiz:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("QUIZ_VIEW", object)',
            provider: QuizProvider::class,
        ),
        new Post(
            uriTemplate: '/quizzes',
            normalizationContext: [
                'groups' => ['quiz:read'],
                'openapi_definition_name' => 'Read',
            ],
            denormalizationContext: [
                'groups' => ['quiz:write'],
                'openapi_definition_name' => 'Write',
            ],
            securityPostDenormalize: 'is_granted("QUIZ_CREATE", object.deck)',
            processor: QuizProcessor::class,
        ),
        new Delete(
            uriTemplate: '/quizzes/{id}',
            uriVariables: [
                'id' => new Link(fromClass: Quiz::class),
            ],
            normalizationContext: [
                'groups' => ['quiz:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("QUIZ_DELETE", object)',
            provider: QuizProvider::class,
            processor: QuizProcessor::class,
        ),
    ],
)]
final class Quiz
{
    #[Groups(['quiz:read'])]
    public Uuid $id;

    #[Groups(['quiz:read', 'quiz:write'])]
    public ?Deck $deck = null;

    #[Groups(['quiz:read', 'quiz:write'])]
    #[Range(min: 10, max: 100)]
    public int $maxQuestions = 100;

    #[Groups(['quiz:read'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['quiz:read'])]
    public ?DateTimeImmutable $startedAt = null;

    #[Groups(['quiz:read'])]
    public ?DateTimeImmutable $endedAt = null;
}
