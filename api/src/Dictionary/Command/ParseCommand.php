<?php

declare(strict_types=1);

namespace App\Dictionary\Command;

use App\Dictionary\Parser\JMDictParser;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:parse',
    description: 'Parse JMDict file',
)]
final class ParseCommand extends Command
{
    public function __construct(
        private readonly JMDictParser $parser,
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $projectDir,
    ) {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->parser->parse("$this->projectDir/data/JMdict.xml");

        return Command::SUCCESS;
    }
}
