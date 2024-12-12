<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\Translation;
use Override;
use Zenstruck\Foundry\ObjectFactory;

/**
 * @extends ObjectFactory<Translation>
 */
final class TranslationFactory extends ObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Translation::class;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function defaults(): array
    {
        return [
            // 'language' => self::faker()->randomElement(['eng', 'fre']),
            'value' => self::faker()->word(),
        ];
    }
}
