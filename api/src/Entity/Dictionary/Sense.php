<?php

declare(strict_types=1);

namespace App\Entity\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Uid\Uuid;

#[Entity]
class Sense
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    public function __construct(
        #[ManyToOne(inversedBy: 'senses')]
        public Entry $entry,

        #[Column]
        public array $partsOfSpeech,

        #[Column(nullable: true)]
        public ?string $fieldOfApplication,

        #[Column(nullable: true)]
        public ?string $dialect,

        #[Column(nullable: true)]
        public ?string $info,

        #[Column]
        public array $kanjiElements,

        #[Column]
        public array $readingElements,

        #[Column]
        public array $referencedElements,

        #[Column]
        public array $antonyms,

        #[OneToMany(targetEntity: Translation::class, mappedBy: 'sense', cascade: ['persist', 'remove'])]
        public Collection $translations = new ArrayCollection(),
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
