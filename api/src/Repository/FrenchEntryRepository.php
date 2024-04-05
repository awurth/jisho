<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FrenchEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FrenchEntry>
 *
 * @method FrenchEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method FrenchEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method FrenchEntry[]    findAll()
 * @method FrenchEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class FrenchEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FrenchEntry::class);
    }
}
