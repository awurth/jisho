<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DictionaryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DictionaryRepository::class)]
#[ApiResource]
#[GetCollection(
    normalizationContext: [
        'groups' => ['dictionary:read'],
        'openapi_definition_name' => 'Collection-Read',
    ],
    security: "is_granted('ROLE_USER')",
)]
class Dictionary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['dictionary:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['dictionary:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Blameable(on: 'create')]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }
}
