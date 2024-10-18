<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory;

use App\Common\Entity\User;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return User::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'roles' => [],
            'name' => self::faker()->name(),
        ];
    }
}
