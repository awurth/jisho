<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\JapaneseEntryTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JapaneseEntryTag>
 *
 * @method JapaneseEntryTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method JapaneseEntryTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method JapaneseEntryTag[]    findAll()
 * @method JapaneseEntryTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class JapaneseEntryTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JapaneseEntryTag::class);
    }
}
