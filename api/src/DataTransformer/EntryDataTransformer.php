<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\Dictionary\Entry;
use App\Entity\Dictionary\KanjiElement;
use App\Entity\Dictionary\ReadingElement;
use App\Entity\Dictionary\Sense;
use App\Entity\Dictionary\Translation;
use Doctrine\Common\Collections\ArrayCollection;
use function Functional\map;

final readonly class EntryDataTransformer
{
    public function transformToEntity(array $data): Entry
    {
        $entry = new Entry($data['sequenceId']);

        $kanjiElements = map(
            $data['kanjiElements'],
            static fn (array $kanjiElement): KanjiElement => new KanjiElement(
                $entry,
                $kanjiElement['value'],
                $kanjiElement['info'],
                $kanjiElement['priority'],
            ),
        );

        $readingElements = map(
            $data['readingElements'],
            static fn (array $readingElement): ReadingElement => new ReadingElement(
                $entry,
                $readingElement['kana'],
                $readingElement['romaji'] ?? '',
                $readingElement['info'],
                $readingElement['priority'],
                $readingElement['nokanji'],
                $readingElement['relatedKanjis'],
            ),
        );

        $senses = map(
            $data['senses'],
            static function (array $senseData) use ($entry): Sense {
                $sense = new Sense(
                    $entry,
                    $senseData['partsOfSpeech'],
                    $senseData['fieldOfApplication'],
                    $senseData['dialect'],
                    $senseData['info'],
                    $senseData['relatedKanjis'],
                    $senseData['relatedReadings'],
                    $senseData['references'],
                    $senseData['antonyms'],
                );

                $translations = map(
                    $senseData['translations'],
                    static fn (array $translation): Translation => new Translation(
                        $sense,
                        $translation['value'],
                        $translation['language'],
                    ),
                );

                $sense->translations = new ArrayCollection($translations);

                return $sense;
            },
        );

        $entry->kanjiElements = new ArrayCollection($kanjiElements);
        $entry->readingElements = new ArrayCollection($readingElements);
        $entry->senses = new ArrayCollection($senses);

        return $entry;
    }
}
