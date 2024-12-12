<?php

declare(strict_types=1);

namespace App\Common\Repository\Dictionary;

use App\Common\Entity\Dictionary\Entry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entry>
 */
final class EntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    /**
     * @return Entry[]
     */
    public function getBatch(int $offset, int $limit): array
    {
        return $this->createQueryBuilder(alias: 'entry')
            ->orderBy('entry.sequenceId')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
