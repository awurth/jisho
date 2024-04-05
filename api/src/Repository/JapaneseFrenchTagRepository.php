<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\JapaneseFrenchTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JapaneseFrenchTag>
 *
 * @method JapaneseFrenchTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method JapaneseFrenchTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method JapaneseFrenchTag[]    findAll()
 * @method JapaneseFrenchTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class JapaneseFrenchTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JapaneseFrenchTag::class);
    }
}
