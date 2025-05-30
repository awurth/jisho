<?php

declare(strict_types=1);

namespace App\Quiz\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Deck\ApiResource\Card;
use App\Quiz\Exception\QuestionAlreadyAnsweredException;
use App\Quiz\Exception\QuizEndedException;
use App\Quiz\State\PostQuestionProcessor;
use App\Quiz\State\QuestionAnswerProcessor;
use App\Quiz\State\QuestionProvider;
use App\Quiz\Validator\CorrectAnswer;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        // new GetCollection(
        //     uriTemplate: '/quizzes/{quizId}/questions',
        //     uriVariables: [
        //         'quizId' => new Link(fromClass: Quiz::class),
        //     ],
        //     normalizationContext: [
        //         'groups' => ['question:read'],
        //         'openapi_definition_name' => 'Read',
        //     ],
        //     security: 'is_granted("ROLE_USER")',
        //     provider: QuestionProvider::class,
        // ),
        // new Get(
        //     uriTemplate: '/quizzes/{quizId}/questions/{id}',
        //     uriVariables: [
        //         'quizId' => new Link(fromClass: Quiz::class),
        //         'id' => new Link(fromClass: Question::class),
        //     ],
        //     normalizationContext: [
        //         'groups' => ['question:read'],
        //         'openapi_definition_name' => 'Read',
        //     ],
        //     security: 'is_granted("QUESTION_VIEW", object)',
        //     provider: QuestionProvider::class,
        // ),
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
            security: 'is_granted("QUESTION_CREATE", object.quiz)',
            provider: QuestionProvider::class,
            processor: PostQuestionProcessor::class,
        ),
        new Patch(
            uriTemplate: '/quizzes/{quizId}/questions/{id}',
            uriVariables: [
                'quizId' => new Link(fromClass: Quiz::class),
                'id' => new Link(fromClass: Question::class),
            ],
            exceptionToStatus: [
                QuestionAlreadyAnsweredException::class => 423,
                QuizEndedException::class => 423,
            ],
            normalizationContext: [
                'groups' => ['question:read'],
                'openapi_definition_name' => 'Read',
            ],
            denormalizationContext: [
                'groups' => ['question:write', 'question:answer'],
                'openapi_definition_name' => 'Write',
            ],
            security: 'is_granted("QUESTION_ANSWER", object)',
            provider: QuestionProvider::class,
            processor: QuestionAnswerProcessor::class,
        ),
    ],
)]
#[CorrectAnswer(groups: ['answer'])]
final class Question
{
    #[Groups(['question:read'])]
    public Uuid $id;

    #[Groups(['question:read'])]
    public Card $card;

    #[Groups(['question:read'])]
    public int $position;

    #[Groups(['question:answered:read'])]
    public ?DateTimeImmutable $answeredAt = null;

    #[Groups(['question:answered:read', 'question:answer'])]
    public string $answer = '';

    #[Groups(['question:skipped:read'])]
    public ?DateTimeImmutable $skippedAt = null;

    #[Groups(['question:answer'])]
    public bool $skipped = false;

    public function __construct(public readonly Quiz $quiz)
    {
    }
}
