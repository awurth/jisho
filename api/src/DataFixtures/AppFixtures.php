<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Dictionary;
use App\Entity\FrenchEntry;
use App\Entity\JapaneseEntry;
use App\Entity\JapaneseEntryTag;
use App\Entity\JapaneseFrenchAssociation;
use App\Entity\Tag;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use function array_column;
use function array_key_exists;
use function array_map;
use function explode;
use function Functional\flat_map;
use function Functional\unique;

final class AppFixtures extends Fixture
{
    public function __construct(
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $projectDir,
    ) {
    }

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = UserFactory::createOne([
            'email' => 'alexis.wurth57@gmail.com',
        ]);

        $dictionary = new Dictionary();
        $dictionary->owner = $user->object();
        $dictionary->name = 'Japonais';

        $this->createEntries($manager, $dictionary);

        $manager->persist($dictionary);

        $manager->flush();
    }

    private function createEntries(ObjectManager $manager, Dictionary $dictionary): void
    {
        $entries = require $this->projectDir.'/entries.php';

        $allTagsNames = array_column($entries, 2);
        $allTagsNames = flat_map($allTagsNames, static fn (string $value): array => array_map(trim(...), explode(',', $value)));
        $allTagsNames = unique($allTagsNames);

        $allTags = [];
        foreach ($allTagsNames as $name) {
            $allTags[$name] = new Tag($dictionary, $name);

            $manager->persist($allTags[$name]);
        }

        foreach ($entries as $entry) {
            $japanese = $entry[0];
            $french = $entry[1];
            $tags = array_key_exists(2, $entry) ? array_map(trim(...), explode(',', $entry[2])) : [];

            $frenchWords = array_map(trim(...), explode(',', (string) $french));

            $japaneseEntry = new JapaneseEntry($dictionary, $japanese);

            $manager->persist($japaneseEntry);

            foreach ($frenchWords as $frenchWord) {
                $frenchEntry = new FrenchEntry($dictionary, $frenchWord);
                $japaneseFrenchAssociation = new JapaneseFrenchAssociation($japaneseEntry, $frenchEntry);

                $manager->persist($frenchEntry);
                $manager->persist($japaneseFrenchAssociation);
            }

            foreach ($tags as $name) {
                $japaneseEntryTag = new JapaneseEntryTag($japaneseEntry, $allTags[$name]);

                $manager->persist($japaneseEntryTag);
            }
        }
    }
}
