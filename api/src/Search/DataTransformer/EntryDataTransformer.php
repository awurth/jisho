<?php

declare(strict_types=1);

namespace App\Search\DataTransformer;

use App\Entity\Dictionary\Entry;
use App\Entity\Dictionary\KanjiElement;
use App\Entity\Dictionary\ReadingElement;
use App\Entity\Dictionary\Sense;
use App\Entity\Dictionary\Translation;
use function Functional\map;

final readonly class EntryDataTransformer
{
    public function transformToSearchArray(Entry ...$entries): array
    {
        return map($entries, static fn (Entry $entry): array => [
            'id' => (string) $entry->getId(),
            'sequenceId' => $entry->sequenceId,
            'kanji' => map($entry->kanjiElements, static fn (KanjiElement $element): array => [
                'id' => (string) $element->getId(),
                'value' => $element->value,
                'info' => $element->info,
                'priority' => $element->priority,
            ]),
            'readings' => map($entry->readingElements, static fn (ReadingElement $element): array => [
                'id' => (string) $element->getId(),
                'kana' => $element->kana,
                'romaji' => $element->romaji,
                'info' => $element->info,
                'priority' => $element->priority,
                'notTrueKanjiReading' => $element->notTrueKanjiReading,
                'kanji' => $element->kanjiElements,
            ]),
            'senses' => map($entry->senses, static fn (Sense $sense): array => [
                'id' => (string) $sense->getId(),
                'partsOfSpeech' => $sense->partsOfSpeech,
                'fieldOfApplication' => $sense->fieldOfApplication,
                'dialect' => $sense->dialect,
                'info' => $sense->info,
                'kanji' => $sense->kanjiElements,
                'readings' => $sense->readingElements,
                'references' => $sense->referencedElements,
                'antonyms' => $sense->antonyms,
                'translations' => map($sense->translations, static fn (Translation $translation): array => [
                    'id' => (string) $translation->getId(),
                    'value' => $translation->value,
                    'language' => $translation->language,
                ]),
            ]),
        ]);
    }
}
