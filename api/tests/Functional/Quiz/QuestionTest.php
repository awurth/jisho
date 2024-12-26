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
        $client->request('POST', "/quizzes/{$quiz->getId()}/questions", [
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
        $client->request('POST', "/quizzes/{$quiz->getId()}/questions", [
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
        $client->request('POST', "/quizzes/{$quiz->getId()}/questions", [
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
        $client->request('POST', "/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertJsonEquals([
            'id' => (string) $question->getId(),
            'createdAt' => $question->createdAt->format(DateTimeInterface::ATOM),
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
        $client->request('POST', "/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(201);

        $newQuestion = QuestionFactory::find([
            'card' => $newQuestionCard,
        ]);

        self::assertNotSame((string) $newQuestion->getId(), (string) $answeredQuestion->getId());
        self::assertJsonEquals([
            'id' => (string) $newQuestion->getId(),
            'createdAt' => $newQuestion->createdAt->format(DateTimeInterface::ATOM),
        ]);
    }

    public function testPostQuestionCreatesNewQuestionIfNotExists(): void
    {
        $quiz = QuizFactory::createOne();
        CardFactory::createOne([
            'deck' => $quiz->deck,
        ]);

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        $client->request('POST', "/quizzes/{$quiz->getId()}/questions", [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(201);

        $question = QuestionFactory::first();

        self::assertJsonEquals([
            'id' => (string) $question->getId(),
            'createdAt' => $question->createdAt->format(DateTimeInterface::ATOM),
        ]);
    }

    public function testPatchQuestionWhenNotAuthenticated(): void
    {
        $question = QuestionFactory::createOne();

        $client = self::createClient();
        self::patch($client, "/quizzes/{$question->quiz->getId()}/questions/{$question->getId()}", []);

        self::assertResponseStatusCodeSame(401);
    }

    public function testPatchQuestionWithInvalidId(): void
    {
        $quiz = QuizFactory::createOne();

        $client = self::createClient();
        self::patch($client, "/quizzes/{$quiz->getId()}/questions/1", []);

        self::assertResponseStatusCodeSame(404);
    }

    public function testPatchQuestionOfAnotherUserQuiz(): void
    {
        $user = UserFactory::createOne();
        $question = QuestionFactory::createOne();

        $client = self::createClient();
        $client->loginUser($user);
        self::patch($client, "/quizzes/{$question->quiz->getId()}/questions/{$question->getId()}", []);

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

        $client = self::createClient();
        $client->loginUser($quiz->deck->owner);
        self::patch($client, "/quizzes/{$question->quiz->getId()}/questions/{$question->getId()}", []);

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

        $client = self::createClient();
        $client->loginUser($question->quiz->deck->owner);
        self::patch($client, "/quizzes/{$question->quiz->getId()}/questions/{$question->getId()}", []);

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

        $client = self::createClient();
        $client->loginUser($question->quiz->deck->owner);
        self::patch($client, "/quizzes/{$question->quiz->getId()}/questions/{$question->getId()}", [
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
