<?php

declare(strict_types=1);

namespace App\Dictionary\Command;

use App\Dictionary\Search\Indexation\DictionaryIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:index',
    description: 'Index dictionary entries',
)]
final class IndexCommand extends Command
{
    public function __construct(private readonly DictionaryIndexer $indexer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->indexer->indexAll();

        return Command::SUCCESS;
    }
}
