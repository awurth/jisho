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

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'sequenceId' => self::faker()->randomNumber(),
        ];
    }
}
