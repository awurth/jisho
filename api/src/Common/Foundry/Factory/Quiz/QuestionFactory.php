<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Quiz;

use App\Common\Entity\Quiz\Question;
use App\Common\Foundry\Factory\Deck\CardFactory;
use DateTimeImmutable;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Question>
 */
final class QuestionFactory extends PersistentProxyObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Question::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'quiz' => QuizFactory::new(),
            'card' => CardFactory::new(),
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }
}
