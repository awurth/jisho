<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Reading
{
    public function __construct(
        #[Groups(['card:read', 'entry:read', 'question:read'])]
        public string $kana,
        #[Groups(['card:read', 'entry:read', 'question:read'])]
        public string $romaji,
        #[Groups(['card:read', 'entry:read', 'question:read'])]
        public string $info,
    ) {
    }
}
