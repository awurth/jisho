<?php

declare(strict_types=1);

namespace App\Dictionary\JMDict\DataMapper;

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
use InvalidArgumentException;
use function Functional\filter;
use function Functional\map;
use function Functional\some;

final readonly class EntryDataMapper
{
    public function __construct(private KanaToRomajiTransliterator $transliterator)
    {
    }

    public function mapDtoToEntity(Entry $entryDto, EntryEntity $entryEntity): void
    {
        $entryEntity->sequenceId = $entryDto->sequenceId;

        $entryEntity->kanjiElements = map($entryDto->kanjiElements, static fn (KanjiElement $kanjiElementDto): KanjiElementEntity => new KanjiElementEntity(
            value: $kanjiElementDto->value,
            info: $kanjiElementDto->info,
            priority: $kanjiElementDto->priority,
        ));

        $entryEntity->readingElements = map($entryDto->readingElements, fn (ReadingElement $readingElementDto): ReadingElementEntity => new ReadingElementEntity(
            kana: $readingElementDto->kana,
            romaji: $this->transliterator->transliterate($readingElementDto->kana),
            info: $readingElementDto->info,
            priority: $readingElementDto->priority,
            notTrueKanjiReading: $readingElementDto->noKanji,
            kanjiElements: $readingElementDto->relatedKanjis,
        ));

        $senses = filter($entryDto->senses, static fn (Sense $senseDto): bool => some($senseDto->translations, static fn (Translation $translationDto): bool => 'eng' === $translationDto->language));

        if ([] === $senses) {
            throw new InvalidArgumentException('Entry should have at least one english translation.');
        }

        $entryEntity->senses = map($senses, static function (Sense $senseDto): SenseEntity {
            $translations = filter($senseDto->translations, static fn (Translation $translationDto): bool => 'eng' === $translationDto->language);

            return new SenseEntity(
                partsOfSpeech: $senseDto->partsOfSpeech,
                fieldOfApplication: $senseDto->fieldOfApplication,
                dialect: $senseDto->dialect,
                misc: $senseDto->misc,
                info: $senseDto->info,
                kanjiElements: $senseDto->relatedKanjis,
                readingElements: $senseDto->relatedReadings,
                referencedElements: $senseDto->references,
                antonyms: $senseDto->antonyms,
                translations: map($translations, static fn (Translation $translationDto): TranslationEntity => new TranslationEntity(
                    value: $translationDto->value,
                    language: $translationDto->language,
                )),
            );
        });
    }
}
