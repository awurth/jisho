<?php

declare(strict_types=1);

namespace App\Common\DataFixtures;

use App\Common\Foundry\Factory\Deck\CardFactory;
use App\Common\Foundry\Factory\Deck\DeckFactory;
use App\Common\Foundry\Factory\Dictionary\EntryFactory;
use App\Common\Foundry\Factory\Quiz\QuizFactory;
use App\Common\Foundry\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class AppFixtures extends Fixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = UserFactory::createOne([
            'email' => 'alexis.wurth57@gmail.com',
        ]);

        $deck = DeckFactory::createOne([
            'name' => 'Main',
            'owner' => $user,
        ]);

        $entries = EntryFactory::randomSet(20);

        foreach ($entries as $entry) {
            CardFactory::createOne([
                'deck' => $deck,
                'entry' => $entry,
            ]);
        }

        QuizFactory::createOne([
            'deck' => $deck,
        ]);
    }
}
