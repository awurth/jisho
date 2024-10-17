<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

use App\Common\Repository\Dictionary\TranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: TranslationRepository::class)]
class Translation
{
    #[Id]
    #[Column(type: 'uuid')]
    private Uuid $id;

    #[ManyToOne(inversedBy: 'translations')]
    #[JoinColumn(nullable: false)]
    public Sense $sense;

    #[Column(type: Types::TEXT)]
    public string $value;

    #[Column]
    public string $language = 'eng';

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
