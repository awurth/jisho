<?php

declare(strict_types=1);

namespace App\Parser;

use App\DataTransformer\EntryDataTransformer;
use Doctrine\ORM\EntityManagerInterface;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;
use XMLReader;
use function count;
use function dd;
use function Functional\filter;
use function in_array;

final readonly class JMDictParser
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntryDataTransformer $entryDataTransformer
    )
    {
    }

    public function parse(): void
    {
        $xml = XMLReader::open('/srv/api/JMdict.xml');

        do {
            $xml->read();
        } while ('entry' !== $xml->name);

        $counter = 0;
        while ('entry' === $xml->name) {
            $entry = $this->parseEntry($xml->readOuterXml());
            $entry = $this->entryDataTransformer->transformToEntity($entry);

            $this->entityManager->persist($entry);

            if ($counter > 500) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $counter = 0;
            } else {
                $counter++;
            }

            $xml->next('entry');
            unset($entry);
        }
    }

    private function parseEntry(string $xml): array
    {
        $crawler = new Crawler($xml, useHtml5Parser: false);

        $sequenceId = (int) $crawler->filter('ent_seq')->text();

        $kanjiElements = $crawler->filter('k_ele')->each(static function (Crawler $element): array {
            $value = $element->filter('keb');
            $info = $element->filter('ke_inf');
            $priority = $element->filter('ke_pri');

            return [
                'value' => $value->text(),
                'info' => $info->count() > 0 ? $info->text() : null,
                'priority' => $priority->count() > 0 ? $priority->text() : null,
            ];
        });

        $readingElements = $crawler->filter('r_ele')->each(static function (Crawler $element): array {
            $kana = $element->filter('reb');
            $nokanji = $element->filter('re_nokanji');
            $relatedKanjis = $element->filter('re_restr')->each(static fn (Crawler $element): string => $element->text());
            $info = $element->filter('re_inf');
            $priority = $element->filter('re_pri');

            return [
                'kana' => $kana->text(),
                'nokanji' => $nokanji->count() > 0,
                'relatedKanjis' => $relatedKanjis,
                'info' => $info->count() > 0 ? $info->text() : null,
                'priority' => $priority->count() > 0 ? $priority->text() : null,
            ];
        });

        $senses = $crawler->filter('sense')->each(static function (Crawler $element): array {
            $relatedKanjis = $element->filter('stagk')->each(static fn (Crawler $element): string => $element->text());
            $relatedReadings = $element->filter('stagr')->each(static fn (Crawler $element): string => $element->text());
            $references = $element->filter('xref')->each(static fn (Crawler $element): string => $element->text());
            $antonyms = $element->filter('ant')->each(static fn (Crawler $element): string => $element->text());
            $partsOfSpeech = $element->filter('pos')->each(static fn (Crawler $element): string => $element->text());
            $fieldOfApplication = $element->filter('field');
            $info = $element->filter('s_inf');
            $dialect = $element->filter('dial');

            $translations = $element->filter('gloss')->each(static function (Crawler $element): array {
                $language = $element->getNode(0)->attributes->getNamedItem('xml:lang');

                return [
                    'language' => $language instanceof DOMNode ? $language->nodeValue : 'eng',
                    'value' => $element->text(),
                ];
            });

            $translations = filter($translations, static fn (array $translation): bool => in_array($translation['language'], ['eng', 'fre'], true));

            if (count($translations) === 0) {
                return [];
            }

            return [
                'relatedKanjis' => $relatedKanjis,
                'relatedReadings' => $relatedReadings,
                'references' => $references,
                'antonyms' => $antonyms,
                'partsOfSpeech' => $partsOfSpeech,
                'fieldOfApplication' => $fieldOfApplication->count() > 0 ? $fieldOfApplication->text() : null,
                'info' => $info->count() > 0 ? $info->text() : null,
                'dialect' => $dialect->count() > 0 ? $dialect->text() : null,
                'translations' => $translations,
            ];
        });

        $senses = filter($senses, static fn (array $sense): bool => [] !== $sense);

        if (count($senses) === 0) {
            return [];
        }

        return [
            'sequenceId' => $sequenceId,
            'kanjiElements' => $kanjiElements,
            'readingElements' => $readingElements,
            'senses' => $senses,
        ];
    }
}
