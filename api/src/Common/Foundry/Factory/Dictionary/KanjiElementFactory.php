<?php

declare(strict_types=1);

namespace App\Common\Foundry\Factory\Dictionary;

use App\Common\Entity\Dictionary\KanjiElement;
use Override;
use Zenstruck\Foundry\ObjectFactory;

/**
 * @extends ObjectFactory<KanjiElement>
 */
final class KanjiElementFactory extends ObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return KanjiElement::class;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function defaults(): array
    {
        return [
            'info' => self::faker()->word(),
            'priority' => self::faker()->word(),
            'value' => self::faker()->word(),
        ];
    }
}
