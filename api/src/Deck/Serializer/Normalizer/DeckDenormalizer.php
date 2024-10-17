<?php

declare(strict_types=1);

namespace App\Deck\Serializer\Normalizer;

use App\Common\Security\Security;
use App\Common\Serializer\Normalizer\AbstractDenormalizer;
use App\Deck\ApiResource\Deck;
use Override;

/**
 * @extends AbstractDenormalizer<Deck>
 */
final class DeckDenormalizer extends AbstractDenormalizer
{
    public function __construct(private readonly Security $security)
    {
    }

    #[Override]
    public function supports(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return Deck::class === $type;
    }

    #[Override]
    protected function updateObject(mixed $object): mixed
    {
        $user = $this->security->getUser();

        $object->owner = $user;

        return $object;
    }

    #[Override]
    protected function getName(): string
    {
        return 'DECK';
    }
}
