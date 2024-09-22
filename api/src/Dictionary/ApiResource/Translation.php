<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Translation
{
    public function __construct(
        #[Groups(['card:read', 'entry:read'])]
        public string $value,
        #[Groups(['card:read', 'entry:read'])]
        public string $language,
    ) {
    }
}
