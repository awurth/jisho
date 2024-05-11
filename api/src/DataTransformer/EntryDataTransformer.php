<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\ApiResource\Dictionary\Entry;
use App\ApiResource\Dictionary\Kanji;
use App\ApiResource\Dictionary\Reading;
use App\ApiResource\Dictionary\Sense;
use App\ApiResource\Dictionary\Translation;
use App\Entity\Dictionary\Entry as EntryEntity;
use App\Entity\Dictionary\KanjiElement;
use App\Entity\Dictionary\ReadingElement;
use App\Entity\Dictionary\Sense as SenseEntity;
use App\Entity\Dictionary\Translation as TranslationEntity;
use function Functional\map;

final readonly class EntryDataTransformer
{
    public function transformEntityToApiResource(EntryEntity $entity): Entry
    {
        $entry = new Entry(
            id: (string) $entity->getId(),
            kanji: map($entity->kanjiElements, static fn (KanjiElement $kanji): Kanji => new Kanji(
                $kanji->value,
                $kanji->info,
            )),
            readings: map($entity->readingElements, static fn (ReadingElement $reading): Reading => new Reading(
                kana: $reading->kana,
                romaji: $reading->romaji,
                info: $reading->info,
            )),
            senses: map($entity->senses, static fn (SenseEntity $sense): Sense => new Sense(
                partsOfSpeech: $sense->partsOfSpeech,
                fieldOfApplication: $sense->fieldOfApplication,
                dialect: $sense->dialect,
                misc: $sense->misc,
                info: $sense->info,
                translations: map($sense->translations, static fn (TranslationEntity $translation): Translation => new Translation(
                    value: $translation->value,
                    language: $translation->language,
                )),
            )),
        );

        $entry->entity = $entity;

        return $entry;
    }
}
