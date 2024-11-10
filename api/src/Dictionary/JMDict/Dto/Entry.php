<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict\Dto;

final readonly class Entry
{
    /**
     * @param string[] $kanjiElements
     * @param string[] $readingElements
     * @param Sense[]  $senses
     */
    public function __construct(
        public int $sequenceId,
        public array $kanjiElements,
        public array $readingElements,
        public array $senses,
    ) {
    }
}
