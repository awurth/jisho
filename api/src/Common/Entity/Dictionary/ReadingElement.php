<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

final class ReadingElement
{
    /**
     * @param string[] $kanjiElements
     */
    public function __construct(
        public string $kana,
        public string $romaji,
        public string $info = '',
        public string $priority = '',
        public bool $notTrueKanjiReading = false,
        public array $kanjiElements = [],
    ) {
    }
}
