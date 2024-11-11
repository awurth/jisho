<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

final class Sense
{
    /**
     * @param string[]      $partsOfSpeech
     * @param string[]      $kanjiElements
     * @param string[]      $readingElements
     * @param string[]      $referencedElements
     * @param string[]      $antonyms
     * @param Translation[] $translations
     */
    public function __construct(
        public array $partsOfSpeech,
        public string $fieldOfApplication,
        public string $dialect,
        public string $misc,
        public string $info,
        public array $kanjiElements,
        public array $readingElements,
        public array $referencedElements,
        public array $antonyms,
        public array $translations,
    ) {
    }
}
