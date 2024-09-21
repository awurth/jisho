<?php

declare(strict_types=1);

namespace App\Common\DataFixtures;

use App\Common\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class AppFixtures extends Fixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'alexis.wurth57@gmail.com',
        ]);
    }
}
