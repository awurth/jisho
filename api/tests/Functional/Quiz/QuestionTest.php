<?php

declare(strict_types=1);

namespace App\Tests\Functional\Quiz;

use App\Common\Foundry\Factory\Deck\CardFactory;
use App\Common\Foundry\Factory\Dictionary\EntryFactory;
use App\Common\Foundry\Factory\Quiz\QuestionFactory;
use App\Common\Foundry\Factory\Quiz\QuizFactory;
use App\Common\Foundry\Factory\UserFactory;
use App\Tests\Functional\ApiTestCase;
use DateTimeImmutable;
use DateTimeInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class QuestionTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testPostQuestionWhenNotAuthenticated(): void
    {
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->request('POST', "/quizzes/{$quiz->id}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(401);
    }

    public function testPostQuestionOnAnotherUserQuiz(): void
    {
        $user = UserFactory::createOne();
        $quiz = QuizFactory::createOne();

        $client = $this->createAuthenticatedClient($user);
        $client->request('POST', "/quizzes/{$quiz->id}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(403);
    }

    public function testPostQuestionIsLockedWhenQuizHasEnded(): void
    {
        $quiz = QuizFactory::createOne([
            'endedAt' => new DateTimeImmutable(),
        ]);

        $client = $this->createAuthenticatedClient($quiz->deck->owner);
        $client->request('POST', "/quizzes/{$quiz->id}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(423);
        self::assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'Quiz already ended.',
            'status' => 423,
        ]);
    }

    public function testPostQuestionReturnsCurrentQuestionIfExistsAndIsNotAnswered(): void
    {
        $quiz = QuizFactory::createOne();
        $card = CardFactory::createOne([
            'deck' => $quiz->deck,
            'entry' => EntryFactory::new()->single()->create(),
        ]);
        $question = QuestionFactory::createOne([
            'quiz' => $quiz,
            'card' => $card,
            'answeredAt' => null,
        ]);

        $client = $this->createAuthenticatedClient($quiz->deck->owner);
        $client->request('POST', "/quizzes/{$quiz->id}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertJsonEquals([
            'id' => (string) $question->id,
            'createdAt' => $question->createdAt->format(DateTimeInterface::ATOM),
            'card' => [
                'entry' => [
                    'kanji' => [
                        [
                            'info' => $card->entry->kanjiElements[0]->info,
                            'value' => $card->entry->kanjiElements[0]->value,
                        ]
                    ],
                    'readings' => [
                        [
                            'info' => $card->entry->readingElements[0]->info,
                            'kana' => $card->entry->readingElements[0]->kana,
                            'romaji' => $card->entry->readingElements[0]->romaji,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testPostQuestionCreatesNewQuestionIfExistsButIsAnswered(): void
    {
        $quiz = QuizFactory::createOne();
        $answeredQuestionCard = CardFactory::createOne([
            'deck' => $quiz->deck,
        ]);
        $newQuestionCard = CardFactory::createOne([
            'deck' => $quiz->deck,
            'entry' => EntryFactory::new()->single()->create(),
        ]);
        $answeredQuestion = QuestionFactory::createOne([
            'quiz' => $quiz,
            'card' => $answeredQuestionCard,
            'answeredAt' => new DateTimeImmutable(),
        ]);

        $client = $this->createAuthenticatedClient($quiz->deck->owner);
        $client->request('POST', "/quizzes/{$quiz->id}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(201);

        $newQuestion = QuestionFactory::find([
            'card' => $newQuestionCard,
        ]);

        self::assertNotSame((string) $newQuestion->id, (string) $answeredQuestion->id);
        self::assertJsonEquals([
            'id' => (string) $newQuestion->id,
            'createdAt' => $newQuestion->createdAt->format(DateTimeInterface::ATOM),
            'card' => [
                'entry' => [
                    'kanji' => [
                        [
                            'info' => $newQuestionCard->entry->kanjiElements[0]->info,
                            'value' => $newQuestionCard->entry->kanjiElements[0]->value,
                        ]
                    ],
                    'readings' => [
                        [
                            'info' => $newQuestionCard->entry->readingElements[0]->info,
                            'kana' => $newQuestionCard->entry->readingElements[0]->kana,
                            'romaji' => $newQuestionCard->entry->readingElements[0]->romaji,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testPostQuestionCreatesNewQuestionIfNotExists(): void
    {
        $quiz = QuizFactory::createOne();
        $card = CardFactory::createOne([
            'deck' => $quiz->deck,
            'entry' => EntryFactory::new()->single()->create(),
        ]);

        $client = $this->createAuthenticatedClient($quiz->deck->owner);
        $client->request('POST', "/quizzes/{$quiz->id}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(201);

        $question = QuestionFactory::first();

        self::assertJsonEquals([
            'id' => (string) $question->id,
            'createdAt' => $question->createdAt->format(DateTimeInterface::ATOM),
            'card' => [
                'entry' => [
                    'kanji' => [
                        [
                            'info' => $card->entry->kanjiElements[0]->info,
                            'value' => $card->entry->kanjiElements[0]->value,
                        ]
                    ],
                    'readings' => [
                        [
                            'info' => $card->entry->readingElements[0]->info,
                            'kana' => $card->entry->readingElements[0]->kana,
                            'romaji' => $card->entry->readingElements[0]->romaji,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testPatchQuestionWhenNotAuthenticated(): void
    {
        $question = QuestionFactory::createOne();

        $client = self::createClient();
        self::patch($client, "/quizzes/{$question->quiz->id}/questions/{$question->id}", []);

        self::assertResponseStatusCodeSame(401);
    }

    public function testPatchQuestionWithInvalidId(): void
    {
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        self::patch($client, "/quizzes/{$quiz->id}/questions/1", []);

        self::assertResponseStatusCodeSame(404);
    }

    public function testPatchQuestionOfAnotherUserQuiz(): void
    {
        $user = UserFactory::createOne();
        $question = QuestionFactory::createOne();

        $client = $this->createAuthenticatedClient($user);
        self::patch($client, "/quizzes/{$question->quiz->id}/questions/{$question->id}", []);

        self::assertResponseStatusCodeSame(403);
    }

    public function testPatchQuestionIsLockedWhenQuizHasEnded(): void
    {
        $quiz = QuizFactory::createOne([
            'endedAt' => new DateTimeImmutable(),
        ]);
        $question = QuestionFactory::createOne([
            'quiz' => $quiz,
        ]);

        $client = $this->createAuthenticatedClient($quiz->deck->owner);
        self::patch($client, "/quizzes/{$question->quiz->id}/questions/{$question->id}", []);

        self::assertResponseStatusCodeSame(423);
        self::assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'Quiz already ended.',
            'status' => 423,
        ]);
    }

    public function testPatchQuestionIsLockedWhenQuestionIsAlreadyAnswered(): void
    {
        $question = QuestionFactory::createOne([
            'answeredAt' => new DateTimeImmutable(),
        ]);

        $client = $this->createAuthenticatedClient($question->quiz->deck->owner);
        self::patch($client, "/quizzes/{$question->quiz->id}/questions/{$question->id}", []);

        self::assertResponseStatusCodeSame(423);
        self::assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'Question already answered.',
            'status' => 423,
        ]);
    }

    public function testPatchQuestionWithWrongAnswer(): void
    {
        $entry = EntryFactory::createOne();
        $card = CardFactory::createOne([
            'entry' => $entry,
        ]);
        $question = QuestionFactory::createOne([
            'card' => $card,
        ]);

        $client = $this->createAuthenticatedClient($question->quiz->deck->owner);
        self::patch($client, "/quizzes/{$question->quiz->id}/questions/{$question->id}", [
            'json' => [
                'answer' => 'wrong',
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'Wrong answer.',
            'status' => 422,
        ]);
    }
}
