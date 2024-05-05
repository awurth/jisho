<?php

declare(strict_types=1);

namespace App\Entity\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Uid\Uuid;

#[Entity]
class Entry
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    public function __construct(
        #[Column]
        public int $sequenceId,

        #[OneToMany(targetEntity: KanjiElement::class, mappedBy: 'entry', cascade: ['persist', 'remove'])]
        public Collection $kanjiElements = new ArrayCollection(),

        #[OneToMany(targetEntity: ReadingElement::class, mappedBy: 'entry', cascade: ['persist', 'remove'])]
        public Collection $readingElements = new ArrayCollection(),

        #[OneToMany(targetEntity: Sense::class, mappedBy: 'entry', cascade: ['persist', 'remove'])]
        public Collection $senses = new ArrayCollection(),
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
