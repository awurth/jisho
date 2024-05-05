<?php

declare(strict_types=1);

namespace App\Repository\Dictionary;

use App\Entity\Dictionary\Entry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entry>
 *
 * @method Entry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entry[]    findAll()
 * @method Entry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class EntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    public function getBatch(int $offset, int $limit): iterable
    {
        return $this->createQueryBuilder(alias: 'e')
            ->addSelect('k')
            ->addSelect('r')
            ->addSelect('s')
            ->addSelect('t')
            ->innerJoin(join: 'e.kanjiElements', alias: 'k')
            ->innerJoin(join: 'e.readingElements', alias: 'r')
            ->innerJoin(join: 'e.senses', alias: 's')
            ->innerJoin(join: 's.translations', alias: 't')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
