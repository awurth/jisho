<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict\Dto;

final readonly class Sense
{
    /**
     * @param string[]      $relatedKanjis
     * @param string[]      $relatedReadings
     * @param string[]      $references
     * @param string[]      $antonyms
     * @param string[]      $partsOfSpeech
     * @param Translation[] $translations
     */
    public function __construct(
        public array $relatedKanjis,
        public array $relatedReadings,
        public array $references,
        public array $antonyms,
        public array $partsOfSpeech,
        public string $fieldOfApplication,
        public string $misc,
        public string $info,
        public string $dialect,
        public array $translations,
    ) {
    }
}
