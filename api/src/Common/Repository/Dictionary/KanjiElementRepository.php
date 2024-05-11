<?php

declare(strict_types=1);

namespace App\Common\Repository\Dictionary;

use App\Common\Entity\Dictionary\KanjiElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KanjiElement>
 *
 * @method KanjiElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method KanjiElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method KanjiElement[]    findAll()
 * @method KanjiElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class KanjiElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KanjiElement::class);
    }
}
