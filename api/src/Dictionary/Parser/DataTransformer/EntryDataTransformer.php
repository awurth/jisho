<?php

declare(strict_types=1);

namespace App\Dictionary\Parser\DataTransformer;

use App\Common\Entity\Dictionary\Entry;
use App\Common\Entity\Dictionary\KanjiElement;
use App\Common\Entity\Dictionary\ReadingElement;
use App\Common\Entity\Dictionary\Sense;
use App\Common\Entity\Dictionary\Translation;
use App\Common\Transliterator\KanaToRomajiTransliterator;
use Doctrine\Common\Collections\ArrayCollection;
use function Functional\map;

final readonly class EntryDataTransformer
{
    public function __construct(private KanaToRomajiTransliterator $transliterator)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function transformToEntity(array $data): Entry
    {
        $entry = new Entry();
        $entry->sequenceId = $data['sequenceId'];

        $kanjiElements = map($data['kanjiElements'], static function (array $data) use ($entry): KanjiElement {
            $element = new KanjiElement();
            $element->entry = $entry;
            $element->value = $data['value'];
            $element->info = $data['info'];
            $element->priority = $data['priority'];

            return $element;
        });

        $readingElements = map($data['readingElements'], function (array $data) use ($entry): ReadingElement {
            $element = new ReadingElement();
            $element->entry = $entry;
            $element->kana = $data['kana'];
            $element->romaji = $this->transliterator->transliterate($data['kana']);
            $element->info = $data['info'];
            $element->priority = $data['priority'];
            $element->notTrueKanjiReading = $data['nokanji'];
            $element->kanjiElements = $data['relatedKanjis'];

            return $element;
        });

        $senses = map($data['senses'], static function (array $data) use ($entry): Sense {
            $sense = new Sense();
            $sense->entry = $entry;
            $sense->partsOfSpeech = $data['partsOfSpeech'];
            $sense->fieldOfApplication = $data['fieldOfApplication'];
            $sense->dialect = $data['dialect'];
            $sense->misc = $data['misc'];
            $sense->info = $data['info'];
            $sense->kanjiElements = $data['relatedKanjis'];
            $sense->readingElements = $data['relatedReadings'];
            $sense->referencedElements = $data['references'];
            $sense->antonyms = $data['antonyms'];

            $translations = map($data['translations'], static function (array $data) use ($sense): Translation {
                $translation = new Translation();
                $translation->sense = $sense;
                $translation->value = $data['value'];
                $translation->language = $data['language'];

                return $translation;
            });

            $sense->translations = new ArrayCollection($translations);

            return $sense;
        });

        $entry->kanjiElements = new ArrayCollection($kanjiElements);
        $entry->readingElements = new ArrayCollection($readingElements);
        $entry->senses = new ArrayCollection($senses);

        return $entry;
    }
}
