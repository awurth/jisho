<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\JapaneseFrenchTagRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: JapaneseFrenchTagRepository::class)]
#[UniqueConstraint(fields: ['japaneseFrenchAssociation', 'tag'])]
class JapaneseFrenchTag
{
    #[ManyToOne(inversedBy: 'tags')]
    #[JoinColumn(nullable: false)]
    public ?JapaneseFrenchAssociation $japaneseFrenchAssociation = null;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public ?Tag $tag = null;

    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
