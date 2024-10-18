<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Deck;

use App\Common\Entity\Deck\Card;
use App\Common\Foundry\Factory\Dictionary\EntryFactory;
use DateTimeImmutable;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Card>
 */
final class CardFactory extends PersistentProxyObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Card::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'addedAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'deck' => DeckFactory::new(),
            'entry' => EntryFactory::new(),
        ];
    }
}
