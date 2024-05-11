<?php

declare(strict_types=1);

namespace App\Entity\Dictionary;

use App\Repository\Dictionary\SenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: SenseRepository::class)]
class Sense
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne(inversedBy: 'senses')]
    public Entry $entry;

    /**
     * @var string[]
     */
    #[Column]
    public array $partsOfSpeech;

    #[Column(nullable: true)]
    public ?string $fieldOfApplication = null;

    #[Column(nullable: true)]
    public ?string $dialect = null;

    #[Column(nullable: true)]
    public ?string $misc = null;

    #[Column(nullable: true)]
    public ?string $info = null;

    /**
     * @var string[]
     */
    #[Column]
    public array $kanjiElements;

    /**
     * @var string[]
     */
    #[Column]
    public array $readingElements;

    /**
     * @var string[]
     */
    #[Column]
    public array $referencedElements;

    /**
     * @var string[]
     */
    #[Column]
    public array $antonyms;

    /**
     * @var Collection<int, Translation>
     */
    #[OneToMany(targetEntity: Translation::class, mappedBy: 'sense', cascade: ['persist', 'remove'])]
    public Collection $translations;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->translations = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
