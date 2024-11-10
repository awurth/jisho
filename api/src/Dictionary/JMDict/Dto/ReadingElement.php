<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict\Dto;

final readonly class ReadingElement
{
    /**
     * @param string[] $relatedKanjis
     */
    public function __construct(
        public string $kana,
        public bool $noKanji,
        public array $relatedKanjis,
        public string $info,
        public string $priority,
    ) {
    }
}
