<?php

declare(strict_types=1);

namespace App\Common\Repository\Dictionary;

use App\Common\Entity\Dictionary\ReadingElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadingElement>
 *
 * @method ReadingElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadingElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadingElement[]    findAll()
 * @method ReadingElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ReadingElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadingElement::class);
    }
}
