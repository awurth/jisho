<?php

declare(strict_types=1);

namespace App\Quiz\Security\Voter;

use App\Common\Entity\User;
use App\Quiz\ApiResource\Quiz;
use DateTimeImmutable;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

/**
 * @extends Voter<string, Quiz>
 */
final class QuizVoter extends Voter
{
    public const string VIEW = 'QUIZ_VIEW';
    public const string EDIT = 'QUIZ_EDIT';
    public const string DELETE = 'QUIZ_DELETE';
    public const string START = 'QUIZ_START';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::START], true)
            && $subject instanceof Quiz;
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if ($user === $subject->deck->owner) {
            return true;
        }

        if (self::START === $attribute) {
            return $subject->startedAt instanceof DateTimeImmutable;
        }

        return false;
    }
}
