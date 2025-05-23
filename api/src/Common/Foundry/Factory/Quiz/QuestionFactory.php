<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Quiz;

use App\Common\Entity\Quiz\Question;
use App\Common\Foundry\Factory\Deck\CardFactory;
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

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function defaults(): array
    {
        return [
            'quiz' => QuizFactory::new(),
            'card' => CardFactory::new(),
            'position' => 0,
        ];
    }
}
