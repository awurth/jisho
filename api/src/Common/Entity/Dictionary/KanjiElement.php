<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

final class KanjiElement
{
    public function __construct(
        public string $value,
        public string $info = '',
        public string $priority = '',
    ) {
    }
}
