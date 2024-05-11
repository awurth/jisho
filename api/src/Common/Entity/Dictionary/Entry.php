<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

use App\Common\Repository\Dictionary\EntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: EntryRepository::class)]
class Entry
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[Column(unique: true)]
    public int $sequenceId;

    /**
     * @var Collection<int, KanjiElement>
     */
    #[OneToMany(targetEntity: KanjiElement::class, mappedBy: 'entry', cascade: ['persist', 'remove'])]
    public Collection $kanjiElements;

    /**
     * @var Collection<int, ReadingElement>
     */
    #[OneToMany(targetEntity: ReadingElement::class, mappedBy: 'entry', cascade: ['persist', 'remove'])]
    public Collection $readingElements;

    /**
     * @var Collection<int, Sense>
     */
    #[OneToMany(targetEntity: Sense::class, mappedBy: 'entry', cascade: ['persist', 'remove'])]
    public Collection $senses;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->kanjiElements = new ArrayCollection();
        $this->readingElements = new ArrayCollection();
        $this->senses = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
