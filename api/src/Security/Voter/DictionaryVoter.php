<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Dictionary;
use App\Entity\User;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

/**
 * @extends Voter<string, Dictionary>
 */
final class DictionaryVoter extends Voter
{
    public const string VIEW = 'DICTIONARY_VIEW';
    public const string CREATE = 'DICTIONARY_CREATE';
    public const string EDIT = 'DICTIONARY_EDIT';
    public const string DELETE = 'DICTIONARY_DELETE';
    public const string CREATE_ENTRY = 'CREATE_DICTIONARY_ENTRY';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::CREATE === $attribute) {
            return true;
        }

        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE_ENTRY], true)
            && $subject instanceof Dictionary;
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (self::CREATE === $attribute) {
            return true;
        }

        return $user === $subject->owner;
    }
}
