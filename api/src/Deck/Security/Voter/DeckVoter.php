<?php

declare(strict_types=1);

namespace App\Deck\Security\Voter;

use App\Common\Entity\User;
use App\Deck\ApiResource\Deck;
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
    public const string CREATE_ENTRY = 'CARD_CREATE';
    public const string CREATE_QUIZ = 'QUIZ_CREATE';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::CREATE === $attribute) {
            return true;
        }

        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE_ENTRY, self::CREATE_QUIZ], true)
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
