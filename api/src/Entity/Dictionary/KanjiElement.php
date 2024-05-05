<?php

declare(strict_types=1);

namespace App\Entity\Dictionary;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity]
class KanjiElement
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne(inversedBy: 'kanjiElements')]
    public Entry $entry;

    #[Column]
    public string $value;

    #[Column(nullable: true)]
    public ?string $info = null;

    #[Column(nullable: true)]
    public ?string $priority = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
