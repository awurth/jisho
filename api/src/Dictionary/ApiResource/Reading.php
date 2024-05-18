<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Reading
{
    public function __construct(
        #[Groups(['deck-entry:read', 'entry:read'])]
        public string $kana,
        #[Groups(['deck-entry:read', 'entry:read'])]
        public string $romaji,
        #[Groups(['deck-entry:read', 'entry:read'])]
        public ?string $info,
    ) {
    }
}
