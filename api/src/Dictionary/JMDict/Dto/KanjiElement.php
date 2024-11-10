<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict\Dto;

final readonly class KanjiElement
{
    public function __construct(
        public string $value,
        public string $info,
        public string $priority,
    ) {
    }
}
