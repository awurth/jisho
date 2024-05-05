<?php

declare(strict_types=1);

namespace App\Command;

use App\Parser\JMDictParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:parse',
    description: 'Parse JMDict file',
)]
final class ParseCommand extends Command
{
    public function __construct(private readonly JMDictParser $parser)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->parser->parse();

        return Command::SUCCESS;
    }
}
