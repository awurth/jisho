<?php

declare(strict_types=1);

namespace App\Common\Entity\Quiz;

use App\Common\Entity\Deck\Deck;
use App\Common\Repository\Quiz\QuizRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[Id]
    #[Column(type: 'uuid')]
    protected(set) Uuid $id;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public Deck $deck;

    #[Column]
    public int $maxQuestions = 0;

    #[Column(updatable: false)]
    public DateTimeImmutable $createdAt;

    #[Column(nullable: true)]
    public ?DateTimeImmutable $startedAt = null;

    #[Column(nullable: true)]
    public ?DateTimeImmutable $endedAt = null;

    // public Collection $tags;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
        // $this->tags = new ArrayCollection();
    }
}
