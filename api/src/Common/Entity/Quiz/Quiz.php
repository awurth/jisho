<?php

declare(strict_types=1);

namespace App\Common\Entity\Quiz;

use App\Common\Entity\Deck\Deck;
use App\Common\Repository\Quiz\QuizRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[Id]
    #[Column(type: 'uuid')]
    public Uuid $id;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public Deck $deck;

    #[Column]
    public int $maxQuestions = 0;

    #[Column(nullable: true)]
    public ?int $numberOfQuestions = null;

    #[Column(nullable: true)]
    public ?int $score = null;

    #[Column(updatable: false)]
    public DateTimeImmutable $createdAt;

    #[Column(nullable: true)]
    public ?DateTimeImmutable $startedAt = null;

    #[Column(nullable: true)]
    public ?DateTimeImmutable $endedAt = null;

    /**
     * @var Collection<int, Question>
     */
    #[OneToMany(targetEntity: Question::class, mappedBy: 'quiz', cascade: ['persist', 'remove'], orphanRemoval: true)]
    public Collection $questions;

    // public Collection $tags;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
        $this->questions = new ArrayCollection();
        // $this->tags = new ArrayCollection();
    }
}
