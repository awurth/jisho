<?php

declare(strict_types=1);

namespace App\Dictionary\Command;

use Exception;
use Meilisearch\Client;
use Override;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use function file_get_contents;
use function implode;
use function json_decode;
use function sprintf;
use const JSON_THROW_ON_ERROR;

#[AsCommand(
    name: 'app:create:search-index',
    description: 'Create a new search index',
)]
final class CreateIndexCommand extends Command
{
    public function __construct(
        private readonly Client $searchClient,
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $projectDir,
    ) {
        parent::__construct();
    }

    #[Override]
    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputOption('delete', null, InputOption::VALUE_NONE, 'If set, the index will be deleted before creation (if it exists)'),
            ]);
    }

    #[Override]
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $style = new SymfonyStyle($input, $output);

        $indexToCreate = 'dictionary';

        try {
            $json = (string) file_get_contents(sprintf('%s/config/search/%s.search.json', $this->projectDir, $indexToCreate));
            $indexConfiguration = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (RuntimeException $exception) {
            $style->error([
                'An error occurred while loading the index configuration:',
                $exception->getMessage(),
            ]);

            return Command::FAILURE;
        }

        try {
            $indexes = $this->searchClient->getIndex($indexToCreate);

            if ($input->getOption('delete')) {
                $deletionRequest = $indexes->delete();

                $this->searchClient->waitForTask($deletionRequest['taskUid']);

                $style->warning(sprintf('The index "%s" has been deleted', $indexToCreate));

                $index = $this->searchClient->createIndex($indexToCreate, [
                    'primaryKey' => $indexConfiguration['primaryKey'],
                ]);

                $this->searchClient->waitForTask($index['taskUid']);

                $style->info(sprintf('The index "%s" has been created', $index['indexUid']));
            }
        } catch (Exception) {
            $index = $this->searchClient->createIndex($indexToCreate, [
                'primaryKey' => $indexConfiguration['primaryKey'],
            ]);

            $this->searchClient->waitForTask($index['taskUid']);

            $style->info(sprintf('The index "%s" has been created', $index['indexUid']));
        }

        try {
            $displayedAttributes = $indexConfiguration['displayedAttributes'];
            $rankingRules = $indexConfiguration['rankingRules'];
            $searchableAttributes = $indexConfiguration['searchableAttributes'];
            $filterableAttributes = $indexConfiguration['filterableAttributes'];
            $sortableAttributes = $indexConfiguration['sortableAttributes'];

            $indexToConfigure = $this->searchClient->getIndex($indexToCreate);
            $indexToConfigure->updateSettings([
                'displayedAttributes' => $displayedAttributes,
                'rankingRules' => $rankingRules,
                'searchableAttributes' => $searchableAttributes,
                'filterableAttributes' => $filterableAttributes,
                'sortableAttributes' => $sortableAttributes,
            ]);

            $this->displayIndexSettings(
                $output,
                $rankingRules,
                $searchableAttributes,
                $filterableAttributes,
                $sortableAttributes,
            );
        } catch (Exception $exception) {
            $style->error([
                'An error occurred while configuring the index:',
                $exception->getMessage(),
            ]);

            return Command::FAILURE;
        }

        $style->success(sprintf('The index "%s" has been (re)configured', $indexToCreate));

        return Command::SUCCESS;
    }

    /**
     * @param string[] $rankingRules
     * @param string[] $searchableAttributes
     * @param string[] $filterableAttributes
     * @param string[] $sortableAttributes
     */
    private function displayIndexSettings(
        OutputInterface $output,
        array $rankingRules,
        array $searchableAttributes,
        array $filterableAttributes,
        array $sortableAttributes,
    ): void {
        $table = new Table($output);
        $table->setHeaders(['Ranking Rules', 'Searchable Attributes', 'Filterable Attributes', 'Sortable Attributes']);
        $table->addRow([
            implode(', ', $rankingRules),
            implode(', ', $searchableAttributes),
            implode(', ', $filterableAttributes),
            implode(', ', $sortableAttributes),
        ]);

        $table->render();
    }
}
