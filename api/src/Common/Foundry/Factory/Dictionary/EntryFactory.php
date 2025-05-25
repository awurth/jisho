<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\Entry;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Entry>
 */
final class EntryFactory extends PersistentProxyObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Entry::class;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function defaults(): array
    {
        return [
            'sequenceId' => self::faker()->unique()->randomNumber(),
            'kanjiElements' => KanjiElementFactory::createRange(1, 4),
            'readingElements' => ReadingElementFactory::createRange(1, 4),
            'senses' => SenseFactory::createRange(1, 4),
        ];
    }

    public function single(): self
    {
        return $this->with([
            'kanjiElements' => KanjiElementFactory::createMany(1),
            'readingElements' => ReadingElementFactory::createMany(1),
            'senses' => SenseFactory::createMany(1, [
                'translations' => TranslationFactory::createMany(1),
            ]),
        ]);
    }

    public function empty(): self
    {
        return $this->with([
            'kanjiElements' => [],
            'readingElements' => [],
            'senses' => [],
        ]);
    }
}
