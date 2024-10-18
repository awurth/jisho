<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\Translation;
use Override;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Translation>
 */
final class TranslationFactory extends PersistentProxyObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Translation::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'language' => self::faker()->text(),
            'sense' => SenseFactory::new(),
            'value' => self::faker()->text(),
        ];
    }
}
