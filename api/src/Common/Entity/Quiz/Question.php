<?php

declare(strict_types=1);

namespace App\Common\Entity\Quiz;

use App\Common\Entity\Deck\Card;
use App\Common\Repository\Quiz\QuestionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[Id]
    #[Column(type: 'uuid')]
    public Uuid $id;

    #[ManyToOne(inversedBy: 'questions')]
    #[JoinColumn(nullable: false)]
    public Quiz $quiz;

    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    public Card $card;

    #[Column()]
    public int $position;

    #[Column(nullable: true)]
    public ?DateTimeImmutable $answeredAt = null;

    #[Column]
    public string $answer = '';

    #[Column(nullable: true)]
    public ?DateTimeImmutable $skippedAt = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }
}
