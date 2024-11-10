<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict\DataTransformer;

use App\Common\Entity\Dictionary\Entry as EntryEntity;
use App\Common\Entity\Dictionary\KanjiElement as KanjiElementEntity;
use App\Common\Entity\Dictionary\ReadingElement as ReadingElementEntity;
use App\Common\Entity\Dictionary\Sense as SenseEntity;
use App\Common\Entity\Dictionary\Translation as TranslationEntity;
use App\Common\Transliterator\KanaToRomajiTransliterator;
use App\Dictionary\JMDict\Dto\Entry;
use App\Dictionary\JMDict\Dto\KanjiElement;
use App\Dictionary\JMDict\Dto\ReadingElement;
use App\Dictionary\JMDict\Dto\Sense;
use App\Dictionary\JMDict\Dto\Translation;
use Doctrine\Common\Collections\ArrayCollection;
use function Functional\map;

final readonly class EntryDataTransformer
{
    public function __construct(private KanaToRomajiTransliterator $transliterator)
    {
    }

    public function transformToEntity(Entry $entryDto): EntryEntity
    {
        $entryEntity = new EntryEntity();
        $entryEntity->sequenceId = $entryDto->sequenceId;

        $kanjiElements = map($entryDto->kanjiElements, static function (KanjiElement $kanjiElementDto) use ($entryEntity): KanjiElementEntity {
            $kanjiElementEntity = new KanjiElementEntity();
            $kanjiElementEntity->entry = $entryEntity;
            $kanjiElementEntity->value = $kanjiElementDto->value;
            $kanjiElementEntity->info = $kanjiElementDto->info;
            $kanjiElementEntity->priority = $kanjiElementDto->priority;

            return $kanjiElementEntity;
        });

        $readingElements = map($entryDto->readingElements, function (ReadingElement $readingElementDto) use ($entryEntity): ReadingElementEntity {
            $readingElementEntity = new ReadingElementEntity();
            $readingElementEntity->entry = $entryEntity;
            $readingElementEntity->kana = $readingElementDto->kana;
            $readingElementEntity->romaji = $this->transliterator->transliterate($readingElementDto->kana);
            $readingElementEntity->info = $readingElementDto->info;
            $readingElementEntity->priority = $readingElementDto->priority;
            $readingElementEntity->notTrueKanjiReading = $readingElementDto->noKanji;
            $readingElementEntity->kanjiElements = $readingElementDto->relatedKanjis;

            return $readingElementEntity;
        });

        $senses = map($entryDto->senses, static function (Sense $senseDto) use ($entryEntity): SenseEntity {
            $senseEntity = new SenseEntity();
            $senseEntity->entry = $entryEntity;
            $senseEntity->partsOfSpeech = $senseDto->partsOfSpeech;
            $senseEntity->fieldOfApplication = $senseDto->fieldOfApplication;
            $senseEntity->dialect = $senseDto->dialect;
            $senseEntity->misc = $senseDto->misc;
            $senseEntity->info = $senseDto->info;
            $senseEntity->kanjiElements = $senseDto->relatedKanjis;
            $senseEntity->readingElements = $senseDto->relatedReadings;
            $senseEntity->referencedElements = $senseDto->references;
            $senseEntity->antonyms = $senseDto->antonyms;

            $translations = map($senseDto->translations, static function (Translation $translationDto) use ($senseEntity): TranslationEntity {
                $translationEntity = new TranslationEntity();
                $translationEntity->sense = $senseEntity;
                $translationEntity->value = $translationDto->value;
                $translationEntity->language = $translationDto->language;

                return $translationEntity;
            });

            $senseEntity->translations = new ArrayCollection($translations);

            return $senseEntity;
        });

        $entryEntity->kanjiElements = new ArrayCollection($kanjiElements);
        $entryEntity->readingElements = new ArrayCollection($readingElements);
        $entryEntity->senses = new ArrayCollection($senses);

        return $entryEntity;
    }
}
