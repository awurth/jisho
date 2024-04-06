<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DictionaryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DictionaryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: [
                'groups' => ['dictionary:read'],
                'openapi_definition_name' => 'Collection-Read',
            ],
            security: "is_granted('DICTIONARY_CREATE')",
        ),
        new Get(
            normalizationContext: [
                'groups' => ['dictionary:read'],
                'openapi_definition_name' => 'Item-Read',
            ],
            security: "is_granted('DICTIONARY_VIEW', object)",
        ),
    ],
)]
class Dictionary
{
    #[Id]
    #[Column(type: 'uuid')]
    #[Groups(['dictionary:read'])]
    private Uuid $id;

    #[Column(length: 255)]
    #[Groups(['dictionary:read'])]
    public ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Blameable(on: 'create')]
    public ?User $owner = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
