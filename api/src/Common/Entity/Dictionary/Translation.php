<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

final class Translation
{
    public function __construct(
        public string $value,
        public string $language = 'eng',
    ) {
    }
}
