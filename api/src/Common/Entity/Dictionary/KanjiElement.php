<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

use App\Common\Repository\Dictionary\KanjiElementRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: KanjiElementRepository::class)]
class KanjiElement
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne(inversedBy: 'kanjiElements')]
    #[JoinColumn(nullable: false)]
    public Entry $entry;

    #[Column]
    public string $value;

    #[Column]
    public string $info = '';

    #[Column]
    public string $priority = '';

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
