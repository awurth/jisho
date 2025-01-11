<?php

declare(strict_types=1);

namespace App\Tests\Functional\Dictionary;

use App\Common\Foundry\Factory\Dictionary\EntryFactory;
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
        $client->request('GET', '/dictionary/entries/1');

        self::assertResponseStatusCodeSame(404);
    }

    public function testGetEntryItemResult(): void
    {
        $entry = EntryFactory::new()->single()->create();

        $client = self::createClient();
        $client->request('GET', "/dictionary/entries/{$entry->id}");

        self::assertResponseStatusCodeSame(200);
        self::assertJsonEquals([
            'id' => (string) $entry->id,
            'kanji' => [
                [
                    'value' => $entry->kanjiElements[0]->value,
                    'info' => $entry->kanjiElements[0]->info,
                ],
            ],
            'readings' => [
                [
                    'kana' => $entry->readingElements[0]->kana,
                    'romaji' => $entry->readingElements[0]->romaji,
                    'info' => $entry->readingElements[0]->info,
                ],
            ],
            'senses' => [
                [
                    'partsOfSpeech' => $entry->senses[0]->partsOfSpeech,
                    'fieldOfApplication' => $entry->senses[0]->fieldOfApplication,
                    'dialect' => $entry->senses[0]->dialect,
                    'misc' => $entry->senses[0]->misc,
                    'info' => $entry->senses[0]->info,
                    'translations' => [
                        [
                            'value' => $entry->senses[0]->translations[0]->value,
                            'language' => $entry->senses[0]->translations[0]->language,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
