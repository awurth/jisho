<?php

declare(strict_types=1);

namespace App\Quiz\Security\Voter;

use App\Common\Entity\User;
use App\Quiz\ApiResource\Question;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

/**
 * @extends Voter<string, Question>
 */
final class QuestionVoter extends Voter
{
    public const string VIEW = 'QUESTION_VIEW';
    public const string ANSWER = 'QUESTION_ANSWER';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::ANSWER], true)
            && $subject instanceof Question;
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $user === $subject->quiz?->deck?->owner;
    }
}
