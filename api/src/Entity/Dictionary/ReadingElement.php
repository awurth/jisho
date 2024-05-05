<?php

declare(strict_types=1);

namespace App\Entity\Dictionary;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity]
class ReadingElement
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne(inversedBy: 'readingElements')]
    public Entry $entry;

    #[Column]
    public string $kana;

    #[Column]
    public string $romaji;

    #[Column(nullable: true)]
    public ?string $info;

    #[Column(nullable: true)]
    public ?string $priority;

    #[Column]
    public bool $notTrueKanjiReading = false;

    #[Column]
    public array $kanjiElements = [];

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
