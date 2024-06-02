<?php

declare(strict_types=1);

namespace App\Common\Repository\Quiz;

use App\Common\Entity\Quiz\Quiz;
use App\Common\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    /**
     * @return Quiz[]
     */
    public function findByOwner(User $owner): array
    {
        return $this->createQueryBuilder(alias: 'q')
            ->join('q.deck', 'd')
            ->where('d.owner = :owner')
            ->setParameter('owner', $owner)
            ->getQuery()
            ->getResult();
    }
}
