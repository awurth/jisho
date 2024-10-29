<?php

declare(strict_types=1);

namespace App\Tests\Functional\Quiz;

use App\Common\Foundry\Factory\Deck\DeckFactory;
use App\Common\Foundry\Factory\Quiz\QuizFactory;
use App\Common\Foundry\Factory\UserFactory;
use App\Tests\Functional\ApiTestCase;
use DateTimeInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class QuizTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetQuizCollectionWhenNotAuthenticated(): void
    {
        $client = self::createClient();
        $client->request('GET', '/api/quizzes');

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetQuizCollectionResult(): void
    {
        $quiz = QuizFactory::createOne();
        QuizFactory::createOne();

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('GET', '/api/quizzes');

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            [
                'id' => (string) $quiz->getId(),
                'deck' => "/api/decks/{$quiz->deck->getId()}",
                'maxQuestions' => $quiz->maxQuestions,
                'createdAt' => $quiz->createdAt->format(DateTimeInterface::ATOM),
            ],
        ]);
    }

    public function testGetQuizItemWithInvalidId(): void
    {
        $client = self::createClient();
        $client->request('GET', '/api/quizzes/1');

        self::assertResponseStatusCodeSame(404);
    }

    public function testGetQuizItemWhenNotAuthenticated(): void
    {
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->request('GET', "/api/quizzes/{$quiz->getId()}");

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetQuizItemOfAnotherUser(): void
    {
        $user = UserFactory::createOne();
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('GET', "/api/quizzes/{$quiz->getId()}");

        self::assertResponseStatusCodeSame(403);
    }

    public function testGetQuizItemResult(): void
    {
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('GET', "/api/quizzes/{$quiz->getId()}");

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            'id' => (string) $quiz->getId(),
            'deck' => "/api/decks/{$quiz->deck->getId()}",
            'maxQuestions' => $quiz->maxQuestions,
            'createdAt' => $quiz->createdAt->format(DateTimeInterface::ATOM),
        ]);
    }

    public function testPostQuizWhenNotAuthenticated(): void
    {
        $client = self::createClient();
        $client->request('POST', '/api/quizzes', [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(401);
        QuizFactory::assert()->empty();
    }

    public function testPostQuizOnAnotherUserDeck(): void
    {
        $user = UserFactory::createOne();
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('POST', '/api/quizzes', [
            'json' => [
                'deck' => "/api/decks/{$deck->getId()}",
            ],
        ]);

        self::assertResponseStatusCodeSame(403);
        QuizFactory::assert()->empty();
    }

    public function testPostQuizDoesNotAcceptMaxQuestionsUnder10AndOver100(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('POST', '/api/quizzes', [
            'json' => [
                'deck' => "/api/decks/{$deck->getId()}",
                'maxQuestions' => 9,
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'maxQuestions',
                    'message' => 'This value should be between 10 and 100.',
                ],
            ],
        ]);
        QuizFactory::assert()->empty();

        $client->request('POST', '/api/quizzes', [
            'json' => [
                'deck' => "/api/decks/{$deck->getId()}",
                'maxQuestions' => 101,
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'maxQuestions',
                    'message' => 'This value should be between 10 and 100.',
                ],
            ],
        ]);
        QuizFactory::assert()->empty();
    }

    public function testPostQuizSuccess(): void
    {
        $deck = DeckFactory::createOne();

        $client = self::createClient();
        $client->loginUser($deck->owner);
        $client->request('POST', '/api/quizzes', [
            'json' => [
                'deck' => "/api/decks/{$deck->getId()}",
                'maxQuestions' => 10,
            ],
        ]);

        QuizFactory::assert()->count(1);
        $quiz = QuizFactory::first();

        self::assertResponseStatusCodeSame(201);
        self::assertJsonEquals([
            'id' => (string) $quiz->getId(),
            'deck' => "/api/decks/{$deck->getId()}",
            'maxQuestions' => 10,
            'createdAt' => $quiz->createdAt->format(DateTimeInterface::ATOM),
        ]);
        self::assertNull($quiz->startedAt);
        self::assertNull($quiz->endedAt);
        QuizFactory::assert()->exists($quiz->getId());
    }

    public function testDeleteQuizWhenNotAuthenticated(): void
    {
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->request('DELETE', "/api/quizzes/{$quiz->getId()}");

        self::assertResponseStatusCodeSame(401);
        QuizFactory::assert()->exists($quiz->getId());
    }

    public function testDeleteQuizOfAnotherUserDeck(): void
    {
        $user = UserFactory::createOne();
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('DELETE', "/api/quizzes/{$quiz->getId()}");

        self::assertResponseStatusCodeSame(403);
        QuizFactory::assert()->exists($quiz->getId());
    }

    public function testDeleteQuizWithInvalidId(): void
    {
        $client = self::createClient();
        $client->request('DELETE', '/api/quizzes/1');

        self::assertResponseStatusCodeSame(404);
    }

    public function testDeleteQuizSuccess(): void
    {
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('DELETE', "/api/quizzes/{$quiz->getId()}");

        self::assertResponseStatusCodeSame(204);
        QuizFactory::assert()->notExists($quiz->getId());
    }
}
