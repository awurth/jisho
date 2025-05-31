<?php

declare(strict_types=1);

namespace App\Dictionary\Command;

use App\Dictionary\JMDict\JMDictImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:import',
    description: 'Import JMDict file',
)]
final readonly class ImportCommand
{
    public function __construct(
        private JMDictImporter $importer,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {
    }

    public function __invoke(): int
    {
        $this->importer->import("$this->projectDir/data/JMdict.xml");

        return Command::SUCCESS;
    }
}
