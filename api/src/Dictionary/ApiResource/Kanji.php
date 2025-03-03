<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Kanji
{
    public function __construct(
        #[Groups(['card:read', 'entry:read', 'question:read'])]
        public string $value,
        #[Groups(['card:read', 'entry:read', 'question:read'])]
        public string $info,
    ) {
    }
}
