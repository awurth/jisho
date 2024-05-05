<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DeckRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DeckRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: [
                'groups' => ['deck:read'],
                'openapi_definition_name' => 'Collection-Read',
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Get(
            normalizationContext: [
                'groups' => ['deck:read'],
                'openapi_definition_name' => 'Item-Read',
            ],
            security: "is_granted('DECK_VIEW', object)",
        ),
    ],
)]
class Deck
{
    #[Id]
    #[Column(type: 'uuid')]
    #[Groups(['deck:read'])]
    private Uuid $id;

    #[Column(length: 255)]
    #[Groups(['deck:read'])]
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
