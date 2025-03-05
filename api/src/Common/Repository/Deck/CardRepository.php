<?php

declare(strict_types=1);

namespace App\Common\Repository\Deck;

use App\Common\Entity\Deck\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Card>
 */
final class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    /**
     * @return Card[]
     */
    public function getRandomCards(Uuid $deckId, int $max): array
    {
        $expr = new Expr();
        $queryBuilder = $this->createQueryBuilder(alias: 'card')
            ->where($expr->eq('card.deck', ':deck'))
            ->setParameter(key: 'deck', value: $deckId)
            ->orderBy('RANDOM()')
            ->setMaxResults($max);

        return $queryBuilder->getQuery()->getResult();
    }
}
