<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Dictionary;
use App\Entity\FrenchEntry;
use App\Entity\JapaneseEntry;
use App\Entity\JapaneseFrenchAssociation;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use function array_map;
use function explode;

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

        // $nihon = new JapaneseEntry();
        // $nihon->dictionary = $dictionary;
        // $nihon->value = '日本語';
        //
        // $japonais = new FrenchEntry();
        // $japonais->dictionary = $dictionary;
        // $japonais->value = 'Japonais';
        //
        // $japon = new FrenchEntry();
        // $japon->dictionary = $dictionary;
        // $japon->value = 'Japon';
        //
        // $nihonJaponais = new JapaneseFrenchAssociation();
        // $nihonJaponais->japanese = $nihon;
        // $nihonJaponais->french = $japonais;
        //
        // $nihonJapon = new JapaneseFrenchAssociation();
        // $nihonJapon->japanese = $nihon;
        // $nihonJapon->french = $japon;
        //
        // $kazoku = new JapaneseEntry();
        // $kazoku->dictionary = $dictionary;
        // $kazoku->value = 'かぞく';
        //
        // $famille = new FrenchEntry();
        // $famille->dictionary = $dictionary;
        // $famille->value = 'Famille';
        //
        // $kazokuFamille = new JapaneseFrenchAssociation();
        // $kazokuFamille->japanese = $kazoku;
        // $kazokuFamille->french = $famille;

        $manager->persist($dictionary);
        // $manager->persist($nihon);
        // $manager->persist($japon);
        // $manager->persist($japonais);
        // $manager->persist($nihonJaponais);
        // $manager->persist($nihonJapon);
        // $manager->persist($kazoku);
        // $manager->persist($famille);
        // $manager->persist($kazokuFamille);

        $manager->flush();
    }

    private function createEntries(ObjectManager $manager, Dictionary $dictionary): void
    {
        $entries = require $this->projectDir.'/entries.php';

        foreach ($entries as [$japanese, $french]) {
            $frenchWords = array_map('trim', explode(',', (string) $french));

            $japaneseEntry = new JapaneseEntry($dictionary, $japanese);

            $manager->persist($japaneseEntry);

            foreach ($frenchWords as $frenchWord) {
                $frenchEntry = new FrenchEntry($dictionary, $frenchWord);
                $japaneseFrenchAssociation = new JapaneseFrenchAssociation($japaneseEntry, $frenchEntry);

                $manager->persist($frenchEntry);
                $manager->persist($japaneseFrenchAssociation);
            }
        }
    }
}
