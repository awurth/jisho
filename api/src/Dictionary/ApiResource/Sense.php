<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Sense
{
    /**
     * @param string[]      $partsOfSpeech
     * @param Translation[] $translations
     */
    public function __construct(
        #[Groups('deck-entry:read')]
        public array $partsOfSpeech,
        #[Groups('deck-entry:read')]
        public ?string $fieldOfApplication,
        #[Groups('deck-entry:read')]
        public ?string $dialect,
        #[Groups('deck-entry:read')]
        public ?string $misc,
        #[Groups('deck-entry:read')]
        public ?string $info,
        #[Groups('deck-entry:read')]
        public array $translations,
    ) {
    }
}
