<?php

declare(strict_types=1);

namespace App\Quiz\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Common\Entity\Quiz\Quiz as QuizEntity;
use App\Deck\ApiResource\Deck;
use App\Quiz\State\QuizProcessor;
use App\Quiz\State\QuizProvider;
use App\Quiz\State\QuizStartProcessor;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

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
        new Post(
            uriTemplate: '/quizzes/{id}/start',
            uriVariables: [
                'id' => new Link(fromClass: Quiz::class),
            ],
            normalizationContext: [
                'groups' => ['quiz:read'],
                'openapi_definition_name' => 'Read',
            ],
            security: 'is_granted("QUIZ_START", object)',
            provider: QuizProvider::class,
            processor: QuizStartProcessor::class,
        ),
    ],
)]
final class Quiz
{
    public QuizEntity $entity;

    #[Groups(['quiz:read'])]
    public Uuid $id;

    #[Groups(['quiz:read', 'quiz:write'])]
    public ?Deck $deck = null;

    #[Groups(['quiz:read', 'quiz:write'])]
    public int $maxQuestions = 0;

    #[Groups(['quiz:read'])]
    public DateTimeImmutable $createdAt;

    #[Groups(['quiz:read'])]
    public ?DateTimeImmutable $startedAt = null;

    #[Groups(['quiz:read'])]
    public ?DateTimeImmutable $endedAt = null;
}
