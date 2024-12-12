<?php

declare(strict_types=1);

namespace App\Common\Repository\Quiz;

use App\Common\Entity\Quiz\Quiz;
use App\Common\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
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
        return $this->createQueryBuilder(alias: 'question')
            ->join('question.deck', 'deck')
            ->where('deck.owner = :owner')
            ->setParameter('owner', $owner)
            ->getQuery()
            ->getResult()
        ;
    }
}
