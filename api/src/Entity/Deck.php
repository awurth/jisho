<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DeckRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: DeckRepository::class)]
#[UniqueConstraint(fields: ['name', 'owner'])]
class Deck
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[Column(length: 50)]
    public ?string $name = null;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public ?User $owner = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
