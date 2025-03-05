<?php

declare(strict_types=1);

namespace App\Quiz\Factory;

use App\Common\Entity\Deck\Card;
use App\Common\Entity\Deck\Deck;
use App\Common\Entity\Quiz\Question;
use App\Common\Entity\Quiz\Quiz;
use App\Common\Repository\Deck\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use function Functional\map;

final readonly class QuizFactory
{
    public function __construct(private CardRepository $cardRepository)
    {
    }

    public function create(
        Deck $deck,
        int $maxQuestions,
    ): Quiz {
        $cards = $this->cardRepository->getRandomCards(deckId: $deck->id, max: $maxQuestions);

        $quiz = new Quiz();
        $quiz->deck = $deck;
        $quiz->maxQuestions = $maxQuestions;
        $quiz->questions = new ArrayCollection(map($cards, static function (Card $card, int $index) use ($quiz): Question {
            $question = new Question();
            $question->quiz = $quiz;
            $question->card = $card;
            $question->position = $index;

            return $question;
        }));

        return $quiz;
    }
}
