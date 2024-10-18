<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\KanjiElement;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<KanjiElement>
 */
final class KanjiElementFactory extends PersistentProxyObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return KanjiElement::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'entry' => EntryFactory::new(),
            'info' => self::faker()->text(),
            'priority' => self::faker()->text(),
            'value' => self::faker()->text(),
        ];
    }
}
