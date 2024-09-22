<?php

declare(strict_types=1);

namespace App\Deck\Security\Voter;

use App\Common\Entity\User;
use App\Deck\ApiResource\Card;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

/**
 * @extends Voter<string, Card>
 */
final class CardVoter extends Voter
{
    public const string VIEW = 'CARD_VIEW';
    public const string EDIT = 'CARD_EDIT';
    public const string DELETE = 'CARD_DELETE';

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Card;
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
