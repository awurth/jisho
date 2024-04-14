<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FrenchEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: FrenchEntryRepository::class)]
#[UniqueConstraint(fields: ['dictionary', 'value'])]
class JapaneseEntry
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    /**
     * @param Collection<int, JapaneseFrenchAssociation> $associations
     */
    public function __construct(
        #[ManyToOne]
        #[JoinColumn(nullable: false)]
        public Dictionary $dictionary,

        #[Column(length: 255)]
        public string $value,

        #[OneToMany(targetEntity: JapaneseFrenchAssociation::class, mappedBy: 'japanese')]
        public Collection $associations = new ArrayCollection(),
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
