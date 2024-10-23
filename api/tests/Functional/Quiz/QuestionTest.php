<?php

declare(strict_types=1);

namespace App\Tests\Functional\Quiz;

use App\Common\Foundry\Factory\Deck\CardFactory;
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
        $client->request('POST', "/api/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(401);
    }

    public function testPostQuestionOnAnotherUserQuiz(): void
    {
        $user = UserFactory::createOne();
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        $client->request('POST', "/api/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(403);
    }

    public function testPostQuestionIsLockedWhenQuizHasEnded(): void
    {
        $quiz = QuizFactory::createOne([
            'endedAt' => new DateTimeImmutable(),
        ]);

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('POST', "/api/quizzes/{$quiz->getId()}/questions", [
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
        ]);
        $question = QuestionFactory::createOne([
            'quiz' => $quiz,
            'card' => $card,
            'answeredAt' => null,
        ]);

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('POST', "/api/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertJsonEquals([
            'id' => (string) $question->getId(),
            'card' => "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}",
            'createdAt' => $question->createdAt->format(DateTimeInterface::ATOM),
            'answer' => '',
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
        ]);
        $answeredQuestion = QuestionFactory::createOne([
            'quiz' => $quiz,
            'card' => $answeredQuestionCard,
            'answeredAt' => new DateTimeImmutable(),
        ]);

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('POST', "/api/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        $newQuestion = QuestionFactory::find([
            'card' => $newQuestionCard,
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertNotSame((string) $newQuestion->getId(), (string) $answeredQuestion->getId());
        self::assertJsonEquals([
            'id' => (string) $newQuestion->getId(),
            'card' => "/api/decks/{$newQuestionCard->deck->getId()}/cards/{$newQuestionCard->getId()}",
            'createdAt' => $newQuestion->createdAt->format(DateTimeInterface::ATOM),
            'answer' => '',
        ]);
    }

    public function testPostQuestionCreatesNewQuestionIfNotExists(): void
    {
        $quiz = QuizFactory::createOne();
        $card = CardFactory::createOne([
            'deck' => $quiz->deck,
        ]);

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('POST', "/api/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        $question = QuestionFactory::first();

        self::assertResponseStatusCodeSame(201);
        self::assertJsonEquals([
            'id' => (string) $question->getId(),
            'card' => "/api/decks/{$card->deck->getId()}/cards/{$card->getId()}",
            'createdAt' => $question->createdAt->format(DateTimeInterface::ATOM),
            'answer' => '',
        ]);
    }
}
