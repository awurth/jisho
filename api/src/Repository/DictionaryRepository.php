<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Dictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dictionary>
 *
 * @method Dictionary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dictionary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dictionary[]    findAll()
 * @method Dictionary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class DictionaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dictionary::class);
    }
}
