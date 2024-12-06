<?php

declare(strict_types=1);

namespace App\Common\Repository\Deck;

use App\Common\Entity\Deck\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Card>
 *
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function getRandomCard(Uuid $deckId, Uuid ...$excludedCardsIds): Card
    {
        $expr = new Expr();
        $queryBuilder = $this->createQueryBuilder(alias: 'card')
            ->where($expr->eq('card.deck', ':deck'))
            ->setParameter('deck', $deckId);

        if ([] !== $excludedCardsIds) {
            $queryBuilder
                ->andWhere($expr->notIn('card.id', ':excludedIds'))
                ->setParameter('excludedIds', $excludedCardsIds);
        }

        $queryBuilder
            ->orderBy('RANDOM()')
            ->setMaxResults(1);

        $card = $queryBuilder->getQuery()->getOneOrNullResult();

        if (!$card instanceof Card) {
            throw new RuntimeException('No card found');
        }

        return $card;
    }
}
