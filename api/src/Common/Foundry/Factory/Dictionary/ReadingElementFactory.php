<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\ReadingElement;
use Override;
use Zenstruck\Foundry\ObjectFactory;

/**
 * @extends ObjectFactory<ReadingElement>
 */
final class ReadingElementFactory extends ObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return ReadingElement::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'info' => self::faker()->word(),
            'kana' => self::faker()->word(),
            'kanjiElements' => [],
            'notTrueKanjiReading' => self::faker()->boolean(),
            'priority' => self::faker()->word(),
            'romaji' => self::faker()->word(),
        ];
    }
}
