<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\JapaneseFrenchAssociationRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: JapaneseFrenchAssociationRepository::class)]
#[UniqueConstraint(fields: ['japanese', 'french'])]
class JapaneseFrenchAssociation
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public ?JapaneseEntry $japanese = null;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public ?FrenchEntry $french = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
