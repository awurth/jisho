<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Deck;

use App\Common\Entity\Deck\Deck;
use App\Common\Foundry\Factory\UserFactory;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Deck>
 */
final class DeckFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Deck::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->text(50),
            'owner' => UserFactory::new(),
        ];
    }
}
