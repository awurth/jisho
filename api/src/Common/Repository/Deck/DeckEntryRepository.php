<?php

declare(strict_types=1);

namespace App\Common\Repository\Deck;

use App\Common\Entity\Deck\DeckEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeckEntry>
 *
 * @method DeckEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeckEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeckEntry[]    findAll()
 * @method DeckEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class DeckEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeckEntry::class);
    }
}
