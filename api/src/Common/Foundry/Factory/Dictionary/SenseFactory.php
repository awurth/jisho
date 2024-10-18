<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\Sense;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Sense>
 */
final class SenseFactory extends PersistentProxyObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Sense::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'antonyms' => [],
            'dialect' => self::faker()->text(),
            'entry' => EntryFactory::new(),
            'fieldOfApplication' => self::faker()->text(),
            'info' => self::faker()->text(),
            'kanjiElements' => [],
            'misc' => self::faker()->text(),
            'partsOfSpeech' => [],
            'readingElements' => [],
            'referencedElements' => [],
        ];
    }
}
