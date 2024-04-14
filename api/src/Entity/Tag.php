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

    public function __construct(
        #[Column(length: 255, unique: true)]
        public string $name,

        #[Column(length: 6, nullable: true)]
        public ?string $color = null,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
