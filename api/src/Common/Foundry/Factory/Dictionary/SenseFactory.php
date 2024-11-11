<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\Sense;
use Override;
use Zenstruck\Foundry\ObjectFactory;

/**
 * @extends ObjectFactory<Sense>
 */
final class SenseFactory extends ObjectFactory
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
            'dialect' => self::faker()->word(),
            'fieldOfApplication' => self::faker()->word(),
            'info' => self::faker()->word(),
            'kanjiElements' => [],
            'misc' => self::faker()->word(),
            'partsOfSpeech' => [],
            'readingElements' => [],
            'referencedElements' => [],
            'translations' => TranslationFactory::createRange(1, 4),
        ];
    }
}
