<?php

declare(strict_types=1);

namespace App\Dictionary\Search\DataTransformer;

use App\Common\Entity\Dictionary\Entry;
use App\Common\Entity\Dictionary\KanjiElement;
use App\Common\Entity\Dictionary\ReadingElement;
use App\Common\Entity\Dictionary\Sense;
use App\Common\Entity\Dictionary\Translation;
use function Functional\flat_map;
use function Functional\map;
use function Functional\sort;

final readonly class EntryDataTransformer
{
    /**
     * @return array<string, mixed>
     */
    public function transformToSearchArray(Entry ...$entries): array
    {
        return map($entries, fn (Entry $entry): array => [
            'id' => $entry->sequenceId,
            'senses' => $this->transformSenses($entry->senses),
            'kana' => map($this->sortReadings($entry->readingElements), static fn (ReadingElement $element): string => $element->kana),
            'romaji' => map($this->sortReadings($entry->readingElements), static fn (ReadingElement $element): string => $element->romaji),
            'kanji' => map($entry->kanjiElements, static fn (KanjiElement $element): string => $element->value),
            'entry' => [
                'id' => (string) $entry->getId(),
                'sequenceId' => $entry->sequenceId,
                'kanji' => map($entry->kanjiElements, static fn (KanjiElement $element): array => [
                    'value' => $element->value,
                    'info' => $element->info,
                    'priority' => $element->priority,
                ]),
                'readings' => map($entry->readingElements, static fn (ReadingElement $element): array => [
                    'kana' => $element->kana,
                    'romaji' => $element->romaji,
                    'info' => $element->info,
                    'priority' => $element->priority,
                    'notTrueKanjiReading' => $element->notTrueKanjiReading,
                    'kanji' => $element->kanjiElements,
                ]),
                'senses' => map($entry->senses, static fn (Sense $sense): array => [
                    'partsOfSpeech' => $sense->partsOfSpeech,
                    'fieldOfApplication' => $sense->fieldOfApplication,
                    'dialect' => $sense->dialect,
                    'misc' => $sense->misc,
                    'info' => $sense->info,
                    'kanji' => $sense->kanjiElements,
                    'readings' => $sense->readingElements,
                    'references' => $sense->referencedElements,
                    'antonyms' => $sense->antonyms,
                    'translations' => map($sense->translations, static fn (Translation $translation): array => [
                        'value' => $translation->value,
                        'language' => $translation->language,
                    ]),
                ]),
            ],
        ]);
    }

    /**
     * @param Sense[] $senses
     *
     * @return string[]
     */
    private function transformSenses(iterable $senses): array
    {
        $translations = flat_map($senses, static fn (Sense $sense): array => $sense->translations);
        // $translations = sort($translations, static function (Translation $a, Translation $b): int {
        //     if ($a->language === $b->language) {
        //         return 0;
        //     }
        //
        //     if ('fre' === $a->language) {
        //         return -1;
        //     }
        //
        //     return 1;
        // });

        return map($translations, static fn (Translation $translation): string => $translation->value);
    }

    /**
     * @param ReadingElement[] $readings
     *
     * @return ReadingElement[]
     */
    private function sortReadings(iterable $readings): array
    {
        return sort($readings, static function (ReadingElement $a, ReadingElement $b): int {
            if ($a->notTrueKanjiReading === $b->notTrueKanjiReading) {
                return 0;
            }

            return $a->notTrueKanjiReading ? 1 : -1;
        });
    }
}
