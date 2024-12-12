<?php

declare(strict_types=1);

namespace App\Deck\Validator;

use Attribute;
use Override;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class UniqueCardEntry extends Constraint
{
    public string $message = 'This entry is already in the deck.';

    #[Override]
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
