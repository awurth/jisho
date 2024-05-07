<?php

declare(strict_types=1);

namespace App\ApiResource\Dictionary;

final readonly class Reading
{
    public function __construct(
        public string $kana,
        public string $romaji,
        public ?string $info,
    ) {
    }
}
