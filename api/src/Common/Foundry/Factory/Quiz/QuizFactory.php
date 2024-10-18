<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Quiz;

use App\Common\Entity\Quiz\Quiz;
use App\Common\Foundry\Factory\Deck\DeckFactory;
use DateTimeImmutable;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Quiz>
 */
final class QuizFactory extends PersistentProxyObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Quiz::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'deck' => DeckFactory::new(),
            'maxQuestions' => self::faker()->randomNumber(),
        ];
    }
}
