<?php

declare(strict_types=1);

namespace App\ApiResource\Dictionary;

final readonly class Sense
{
    /**
     * @param string[]      $partsOfSpeech
     * @param Translation[] $translations
     */
    public function __construct(
        public array $partsOfSpeech,
        public ?string $fieldOfApplication,
        public ?string $dialect,
        public ?string $misc,
        public ?string $info,
        public array $translations,
    ) {
    }
}
