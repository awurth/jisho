<?php

declare(strict_types=1);

namespace App\Dictionary\ApiResource\DataTransformer;

use App\Common\Entity\Dictionary\Entry as EntryEntity;
use App\Common\Entity\Dictionary\KanjiElement;
use App\Common\Entity\Dictionary\ReadingElement;
use App\Common\Entity\Dictionary\Sense as SenseEntity;
use App\Common\Entity\Dictionary\Translation as TranslationEntity;
use App\Dictionary\ApiResource\Entry;
use App\Dictionary\ApiResource\Kanji;
use App\Dictionary\ApiResource\Reading;
use App\Dictionary\ApiResource\Sense;
use App\Dictionary\ApiResource\Translation;
use function Functional\map;

final readonly class EntryDataTransformer
{
    public function transformEntityToApiResource(EntryEntity $entity): Entry
    {
        return new Entry(
            id: $entity->getId(),
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
    }
}
