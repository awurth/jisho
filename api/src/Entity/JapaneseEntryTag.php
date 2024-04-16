<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\JapaneseEntryTagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: JapaneseEntryTagRepository::class)]
#[ORM\UniqueConstraint(fields: ['japaneseEntry', 'tag'])]
class JapaneseEntryTag
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        public JapaneseEntry $japaneseEntry,

        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        public Tag $tag,
    ) {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
