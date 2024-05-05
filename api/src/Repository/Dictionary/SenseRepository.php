<?php

declare(strict_types=1);

namespace App\Repository\Dictionary;

use App\Entity\Dictionary\Sense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sense>
 *
 * @method Sense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sense[]    findAll()
 * @method Sense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class SenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sense::class);
    }
}
