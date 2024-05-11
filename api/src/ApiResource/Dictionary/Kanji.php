<?php

declare(strict_types=1);

namespace App\ApiResource\Dictionary;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Kanji
{
    public function __construct(
        #[Groups('deck-entry:read')]
        public string $value,
        #[Groups('deck-entry:read')]
        public ?string $info,
    ) {
    }
}
