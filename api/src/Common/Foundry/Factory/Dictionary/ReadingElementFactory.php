<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\ReadingElement;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ReadingElement>
 */
final class ReadingElementFactory extends PersistentProxyObjectFactory
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
            'entry' => EntryFactory::new(),
            'info' => self::faker()->text(),
            'kana' => self::faker()->text(),
            'kanjiElements' => [],
            'notTrueKanjiReading' => self::faker()->boolean(),
            'priority' => self::faker()->text(),
            'romaji' => self::faker()->text(),
        ];
    }
}
