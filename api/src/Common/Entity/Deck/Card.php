<?php

declare(strict_types=1);

namespace App\Common\Entity\Deck;

use App\Common\Entity\Dictionary\Entry;
use App\Common\Repository\Deck\CardRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: CardRepository::class)]
#[UniqueConstraint(fields: ['deck', 'entry'])]
class Card
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public Deck $deck;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public Entry $entry;

    #[Column(updatable: false)]
    public DateTimeImmutable $addedAt;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->addedAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
