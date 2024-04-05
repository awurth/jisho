<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[Column(length: 255, unique: true)]
    public ?string $name = null;

    #[Column(length: 6, nullable: true)]
    public ?string $color = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
