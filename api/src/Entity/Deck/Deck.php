<?php

declare(strict_types=1);

namespace App\Entity\Deck;

use App\Entity\User;
use App\Repository\Deck\DeckRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: DeckRepository::class)]
#[UniqueConstraint(fields: ['owner', 'name'])]
class Deck
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public User $owner;

    #[Column(length: 50)]
    public string $name;

    #[Column(updatable: false)]
    public DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
