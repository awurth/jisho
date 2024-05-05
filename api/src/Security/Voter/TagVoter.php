<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Tag;
use App\Entity\User;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

/**
 * @extends Voter<string, Tag>
 */
final class TagVoter extends Voter
{
    public const string VIEW = 'TAG_VIEW';
    public const string EDIT = 'TAG_EDIT';
    public const string DELETE = 'TAG_DELETE';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Tag;
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $user === $subject->deck->owner;
    }
}
