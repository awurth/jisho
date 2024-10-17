<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

use App\Common\Repository\Dictionary\ReadingElementRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: ReadingElementRepository::class)]
class ReadingElement
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne(inversedBy: 'readingElements')]
    #[JoinColumn(nullable: false)]
    public Entry $entry;

    #[Column]
    public string $kana;

    #[Column]
    public string $romaji;

    #[Column]
    public string $info = '';

    #[Column]
    public string $priority = '';

    #[Column]
    public bool $notTrueKanjiReading = false;

    /**
     * @var string[]
     */
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
