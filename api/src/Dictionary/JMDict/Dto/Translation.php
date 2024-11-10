<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict\Dto;

final readonly class Translation
{
    public function __construct(
        public string $language,
        public string $value,
    ) {
    }
}
