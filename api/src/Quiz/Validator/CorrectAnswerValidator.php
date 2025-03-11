<?php

declare(strict_types=1);

namespace App\Quiz\Validator;

use App\Common\Entity\Dictionary\Sense as SenseEntity;
use App\Common\Entity\Dictionary\Translation as TranslationEntity;
use App\Common\Entity\Quiz\Question as QuestionEntity;
use App\Common\Repository\Quiz\QuestionRepository;
use App\Quiz\ApiResource\Question;
use Override;
use RuntimeException;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function Functional\some;

final class CorrectAnswerValidator extends ConstraintValidator
{
    public function __construct(private readonly QuestionRepository $questionRepository)
    {
    }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CorrectAnswer) {
            throw new UnexpectedTypeException($constraint, CorrectAnswer::class);
        }

        if (!$value instanceof Question) {
            throw new UnexpectedValueException($value, Question::class);
        }

        $questionEntity = $this->questionRepository->find($value->id);
        if (!$questionEntity instanceof QuestionEntity) {
            throw new RuntimeException('Question not found.');
        }

        if ($value->skipped) {
            return;
        }

        $answer = new UnicodeString($value->answer)->trim()->toString();

        $correct = some(
            $questionEntity->card->entry->senses,
            static fn (SenseEntity $senseEntity): bool => some(
                $senseEntity->translations,
                static fn (TranslationEntity $translationEntity): bool => self::compareAnswerToTranslation($translationEntity->value, $answer),
            ),
        );

        if (!$correct) {
            $this->context->buildViolation($constraint->message)
                ->atPath('answer')
                ->setInvalidValue($value->answer)
                ->addViolation();
        }
    }

    private static function compareAnswerToTranslation(string $translation, string $answer): bool
    {
        return new UnicodeString($translation)
            ->replaceMatches(fromRegexp: '/\([^()]*\)/', to: '')
            ->replace(from: '...', to: '')
            ->trim()
            ->equalsTo($answer);
    }
}
