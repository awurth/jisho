<?php

declare(strict_types=1);

namespace App\Common\Repository\Quiz;

use App\Common\Entity\Quiz\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Question>
 */
final class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findLastUnansweredQuestion(Uuid $quizId): ?Question
    {
        $expr = new Expr();
        $queryBuilder = $this->createQueryBuilder(alias: 'question')
            ->where($expr->eq('question.quiz', ':quiz'))
            ->andWhere($expr->isNull('question.answeredAt'))
            ->setParameter(key: 'quiz', value: $quizId)
            ->orderBy(sort: 'question.position', order: 'ASC')
            ->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
