<?php

declare(strict_types=1);

namespace App\Dictionary\Command;

use App\Dictionary\Search\Indexation\DictionaryIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'app:index',
    description: 'Index dictionary entries',
)]
final readonly class IndexCommand
{
    public function __construct(private DictionaryIndexer $indexer)
    {
    }

    public function __invoke(): int
    {
        $this->indexer->indexAll();

        return Command::SUCCESS;
    }
}
