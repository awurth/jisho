<?php

declare(strict_types=1);

namespace App\Quiz\Validator;

use Attribute;
use Override;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class CorrectAnswer extends Constraint
{
    public string $message = 'Wrong answer.';

    #[Override]
    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
