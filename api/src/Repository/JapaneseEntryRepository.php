<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\JapaneseEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JapaneseEntry>
 *
 * @method JapaneseEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method JapaneseEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method JapaneseEntry[]    findAll()
 * @method JapaneseEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class JapaneseEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JapaneseEntry::class);
    }
}
