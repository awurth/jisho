<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\JapaneseFrenchAssociation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<JapaneseFrenchAssociation>
 *
 * @method JapaneseFrenchAssociation|null find($id, $lockMode = null, $lockVersion = null)
 * @method JapaneseFrenchAssociation|null findOneBy(array $criteria, array $orderBy = null)
 * @method JapaneseFrenchAssociation[]    findAll()
 * @method JapaneseFrenchAssociation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class JapaneseFrenchAssociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JapaneseFrenchAssociation::class);
    }

    public function findByDictionaryQueryBuilder(Uuid $dictionaryId): QueryBuilder
    {
        return $this->createQueryBuilder(alias: 'a')
            ->innerJoin(join: 'a.japanese', alias: 'j')
            ->innerJoin(join: 'a.french', alias: 'f')
            ->where('j.dictionary = :dictionaryId')
            ->setParameter(key: 'dictionaryId', value: $dictionaryId)
        ;
    }

    public function findByJapanese(Uuid $japaneseId): array
    {
        return $this->createQueryBuilder(alias: 'a')
            ->innerJoin(join: 'a.japanese', alias: 'j')
            ->innerJoin(join: 'a.french', alias: 'f')
            ->where('j.id = :japaneseId')
            ->setParameter(key: 'japaneseId', value: $japaneseId)
            ->getQuery()
            ->getResult()
        ;
    }
}
