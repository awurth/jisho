<?php

declare(strict_types=1);

namespace App\Dictionary\Command;

use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function fclose;
use function fopen;
use function fwrite;

#[AsCommand(
    name: 'app:download-jmdict-file',
    description: 'Index dictionary entries',
)]
final readonly class DownloadJMDictFileCommand
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $progressBar = $io->createProgressBar(100);
        $progressBar->start();

        $response = $this->httpClient->request('GET', 'http://ftp.edrdg.org/pub/Nihongo/JMdict_e', [
            'on_progress' => static function (int $dlNow, int $dlSize, array $info) use ($progressBar): void {
                if ($dlSize <= 0) {
                    return;
                }

                $progressBar->setProgress((int) ($dlNow * 100 / $dlSize));
            },
        ]);

        $fileHandle = fopen("$this->projectDir/data/JMdict.xml", 'w');

        if (false === $fileHandle) {
            throw new RuntimeException('Could not open file.');
        }

        foreach ($this->httpClient->stream($response) as $chunk) {
            fwrite($fileHandle, $chunk->getContent());
        }
        fclose($fileHandle);

        return Command::SUCCESS;
    }
}
