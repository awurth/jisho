<?php

declare(strict_types=1);

namespace App\ApiResource\Dictionary;

final readonly class Translation
{
    public function __construct(
        public string $value,
        public string $language,
    ) {
    }
}
