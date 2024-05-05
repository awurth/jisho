<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Deck;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function sprintf;

final readonly class DeckCollectionExtension implements QueryCollectionExtensionInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @phpstan-ignore-next-line
     */
    #[Override]
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = [],
    ): void {
        if (Deck::class !== $resourceClass) {
            return;
        }

        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user instanceof User) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere(sprintf('%s.owner = :owner', $alias))
            ->setParameter('owner', $user)
        ;
    }
}
