<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FrenchEntryRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: FrenchEntryRepository::class)]
#[UniqueConstraint(fields: ['dictionary', 'value'])]
class FrenchEntry
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    public function __construct(
        #[ManyToOne]
        #[JoinColumn(nullable: false)]
        public Dictionary $dictionary,

        #[Column(length: 255)]
        public string $value,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
