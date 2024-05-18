<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Kanji
{
    public function __construct(
        #[Groups(['deck-entry:read', 'entry:read'])]
        public string $value,
        #[Groups(['deck-entry:read', 'entry:read'])]
        public ?string $info,
    ) {
    }
}
