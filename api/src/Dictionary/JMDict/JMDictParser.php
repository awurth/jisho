<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict;

use App\Dictionary\JMDict\Dto\Entry;
use App\Dictionary\JMDict\Dto\KanjiElement;
use App\Dictionary\JMDict\Dto\ReadingElement;
use App\Dictionary\JMDict\Dto\Sense;
use App\Dictionary\JMDict\Dto\Translation;
use DOMDocument;
use DOMNode;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\DomCrawler\Crawler;
use XMLReader;
use function count;
use function Functional\filter;
use function in_array;

#[Exclude]
final class JMDictParser
{
    private ?XMLReader $xml = null;

    public function __construct(private readonly string $filename)
    {
    }

    public function next(): ?Entry
    {
        $node = $this->getNextNode();

        if (!$node instanceof DOMNode) {
            return null;
        }

        return $this->parseEntry($node);
    }

    private function getNextNode(): ?DOMNode
    {
        if (!$this->xml instanceof XMLReader) {
            $xml = XMLReader::open($this->filename);

            if (!$xml instanceof XMLReader) {
                throw new RuntimeException('Could not open XML file.');
            }

            do {
                $xml->read();
            } while ('entry' !== $xml->name);

            $this->xml = $xml;
        } else {
            $this->xml->next('entry');
        }

        if ('entry' !== $this->xml->name) {
            return null;
        }

        $node = $this->xml->expand(new DOMDocument());

        if ($node instanceof DOMNode) {
            return $node;
        }

        return $this->getNextNode();
    }

    private function parseEntry(DOMNode $node): ?Entry
    {
        $crawler = new Crawler(useHtml5Parser: false);
        $crawler->addNode($node);

        $sequenceId = (int) $crawler->filter('ent_seq')->text();

        $kanjiElements = $crawler->filter('k_ele')->each(static function (Crawler $element): KanjiElement {
            $value = $element->filter('keb');
            $info = $element->filter('ke_inf');
            $priority = $element->filter('ke_pri');

            return new KanjiElement(
                value: $value->text(default: ''),
                info: $info->count() > 0 ? $info->text(default: '') : '',
                priority: $priority->count() > 0 ? $priority->text(default: '') : '',
            );
        });

        $readingElements = $crawler->filter('r_ele')->each(static function (Crawler $element): ReadingElement {
            $kana = $element->filter('reb');
            $nokanji = $element->filter('re_nokanji');
            $relatedKanjis = $element->filter('re_restr')->each(static fn (Crawler $element): string => $element->text(default: ''));
            $info = $element->filter('re_inf');
            $priority = $element->filter('re_pri');

            return new ReadingElement(
                kana: $kana->text(default: ''),
                noKanji: $nokanji->count() > 0,
                relatedKanjis: $relatedKanjis,
                info: $info->count() > 0 ? $info->text(default: '') : '',
                priority: $priority->count() > 0 ? $priority->text(default: '') : '',
            );
        });

        $senses = $crawler->filter('sense')->each(static function (Crawler $element): ?Sense {
            $relatedKanjis = $element->filter('stagk')->each(static fn (Crawler $element): string => $element->text(default: ''));
            $relatedReadings = $element->filter('stagr')->each(static fn (Crawler $element): string => $element->text(default: ''));
            $references = $element->filter('xref')->each(static fn (Crawler $element): string => $element->text(default: ''));
            $antonyms = $element->filter('ant')->each(static fn (Crawler $element): string => $element->text(default: ''));
            $partsOfSpeech = $element->filter('pos')->each(static fn (Crawler $element): string => $element->text(default: ''));
            $fieldOfApplication = $element->filter('field');
            $misc = $element->filter('misc');
            $info = $element->filter('s_inf');
            $dialect = $element->filter('dial');

            $translations = $element->filter('gloss')->each(static function (Crawler $element): Translation {
                $language = $element->getNode(0)?->attributes?->getNamedItem('lang')?->nodeValue ?? 'eng';

                return new Translation(
                    language: $language,
                    value: $element->text(default: ''),
                );
            });

            $translations = filter($translations, static fn (Translation $translation): bool => in_array($translation->language, ['eng', 'fre'], true));

            if (count($translations) === 0) {
                return null;
            }

            return new Sense(
                relatedKanjis: $relatedKanjis,
                relatedReadings: $relatedReadings,
                references: $references,
                antonyms: $antonyms,
                partsOfSpeech: $partsOfSpeech,
                fieldOfApplication: $fieldOfApplication->count() > 0 ? $fieldOfApplication->text(default: '') : '',
                misc: $misc->count() > 0 ? $misc->text(default: '') : '',
                info: $info->count() > 0 ? $info->text(default: '') : '',
                dialect: $dialect->count() > 0 ? $dialect->text(default: '') : '',
                translations: $translations,
            );
        });

        $senses = filter($senses, static fn (?Sense $sense): bool => $sense instanceof Sense);

        if (count($senses) === 0) {
            return null;
        }

        return new Entry(
            sequenceId: $sequenceId,
            kanjiElements: $kanjiElements,
            readingElements: $readingElements,
            senses: $senses,
        );
    }
}
