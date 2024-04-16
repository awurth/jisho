<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\ApiResource\Entry;
use App\Entity\User;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

/**
 * @extends Voter<string, Entry>
 */
final class EntryVoter extends Voter
{
    public const string VIEW = 'ENTRY_VIEW';
    public const string EDIT = 'ENTRY_EDIT';
    public const string DELETE = 'ENTRY_DELETE';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Entry;
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $user === $subject->dictionary->owner;
    }
}
