<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Deck;
use App\Entity\User;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

/**
 * @extends Voter<string, Deck>
 */
final class DeckVoter extends Voter
{
    public const string VIEW = 'DECK_VIEW';
    public const string CREATE = 'DECK_CREATE';
    public const string EDIT = 'DECK_EDIT';
    public const string DELETE = 'DECK_DELETE';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::CREATE === $attribute) {
            return true;
        }

        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Deck;
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
