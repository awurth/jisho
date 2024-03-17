<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_83,
        SetList::CODE_QUALITY,
        SetList::TYPE_DECLARATION,
        DoctrineSetList::DOCTRINE_ORM_214,
        PHPUnitSetList::PHPUNIT_100,
        SymfonySetList::SYMFONY_64,
    ]);
};
