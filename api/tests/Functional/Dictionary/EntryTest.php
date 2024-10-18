<?php

declare(strict_types=1);

namespace App\Tests\Functional\Dictionary;

use App\Common\Entity\Dictionary\Entry;
use App\Common\Foundry\Factory\Dictionary\EntryFactory;
use App\Common\Foundry\Factory\Dictionary\KanjiElementFactory;
use App\Common\Foundry\Factory\Dictionary\ReadingElementFactory;
use App\Common\Foundry\Factory\Dictionary\SenseFactory;
use App\Common\Foundry\Factory\Dictionary\TranslationFactory;
use App\Tests\Functional\ApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class EntryTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    public function testGetEntryItemWithInvalidId(): void
    {
        $client = self::createClient();
        $client->request('GET', '/api/dictionary/entries/1');

        self::assertResponseStatusCodeSame(404);
    }

    public function testGetEntryItemResult(): void
    {
        $entry = $this->createEntry();

        $client = self::createClient();
        $client->request('GET', "/api/dictionary/entries/{$entry->getId()}");

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            'id' => (string) $entry->getId(),
            'kanji' => [
                [
                    'value' => $entry->kanjiElements->get(0)?->value,
                    'info' => $entry->kanjiElements->get(0)?->info,
                ],
            ],
            'readings' => [
                [
                    'kana' => $entry->readingElements->get(0)?->kana,
                    'romaji' => $entry->readingElements->get(0)?->romaji,
                    'info' => $entry->readingElements->get(0)?->info,
                ],
            ],
            'senses' => [
                [
                    'partsOfSpeech' => $entry->senses->get(0)?->partsOfSpeech,
                    'fieldOfApplication' => $entry->senses->get(0)?->fieldOfApplication,
                    'dialect' => $entry->senses->get(0)?->dialect,
                    'misc' => $entry->senses->get(0)?->misc,
                    'info' => $entry->senses->get(0)?->info,
                    'translations' => [
                        [
                            'value' => $entry->senses->get(0)?->translations->get(0)?->value,
                            'language' => $entry->senses->get(0)?->translations->get(0)?->language,
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function createEntry(): Entry
    {
        $entry = EntryFactory::createOne();

        KanjiElementFactory::createOne(['entry' => $entry]);
        ReadingElementFactory::createOne(['entry' => $entry]);

        $sense = SenseFactory::createOne(['entry' => $entry]);

        TranslationFactory::createOne(['sense' => $sense]);

        return $entry->_real();
    }
}
