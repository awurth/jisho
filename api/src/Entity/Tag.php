<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\TagRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: TagRepository::class)]
#[UniqueConstraint(fields: ['dictionary', 'name'])]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/dictionaries/{dictionaryId}/tags',
            uriVariables: [
                'dictionaryId' => new Link(toProperty: 'dictionary', fromClass: Dictionary::class),
            ],
            normalizationContext: [
                'groups' => ['tag:read'],
                'openapi_definition_name' => 'Collection-Read',
            ],
            security: "is_granted('ROLE_USER')",
        ),
        // new Get(
        //     uriTemplate: '/dictionaries/{dictionaryId}/tags/{id}',
        //     uriVariables: [
        //         'dictionaryId' => new Link(toProperty: 'dictionary', fromClass: Dictionary::class),
        //         'id' => new Link(fromClass: Tag::class),
        //     ],
        //     normalizationContext: [
        //         'groups' => ['tag:read'],
        //         'openapi_definition_name' => 'Item-Read',
        //     ],
        //     security: "is_granted('TAG_VIEW', object)",
        // ),
    ],
)]
class Tag
{
    #[Id]
    #[Column(type: 'uuid')]
    #[Groups(['tag:read'])]
    private Uuid $id;

    public function __construct(
        #[ManyToOne]
        #[JoinColumn(nullable: false)]
        public Dictionary $dictionary,

        #[Column(length: 255)]
        #[Groups(['tag:read'])]
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
