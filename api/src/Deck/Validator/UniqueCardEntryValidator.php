<?php

declare(strict_types=1);

namespace App\Deck\Validator;

use App\Common\Entity\Deck\Card as CardEntity;
use App\Common\Repository\Deck\CardRepository;
use App\Deck\ApiResource\Card;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class UniqueCardEntryValidator extends ConstraintValidator
{
    public function __construct(private readonly CardRepository $cardRepository)
    {
    }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueCardEntry) {
            throw new UnexpectedTypeException($constraint, UniqueCardEntry::class);
        }

        if (!$value instanceof Card) {
            throw new UnexpectedValueException($value, Card::class);
        }

        $existingCard = $this->cardRepository->findOneBy([
            'deck' => $value->deck->id,
            'entry' => $value->entry->id,
        ]);

        if ($existingCard instanceof CardEntity) {
            $this->context->buildViolation($constraint->message)
                ->atPath('entry')
                ->setInvalidValue($value->entry)
                ->addViolation();
        }
    }
}
