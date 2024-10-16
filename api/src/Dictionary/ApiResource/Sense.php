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
        #[Groups(['card:read', 'entry:read'])]
        public array $partsOfSpeech,
        #[Groups(['card:read', 'entry:read'])]
        public string $fieldOfApplication,
        #[Groups(['card:read', 'entry:read'])]
        public string $dialect,
        #[Groups(['card:read', 'entry:read'])]
        public string $misc,
        #[Groups(['card:read', 'entry:read'])]
        public string $info,
        #[Groups(['card:read', 'entry:read'])]
        public array $translations,
    ) {
    }
}
