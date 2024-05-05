<?php

declare(strict_types=1);

namespace App\Entity\Dictionary;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity]
class Translation
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    public function __construct(
        #[ManyToOne(inversedBy: 'translations')]
        public Sense $sense,

        #[Column]
        public string $value,

        #[Column]
        public string $language = 'eng',
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
