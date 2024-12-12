<?php

declare(strict_types=1);

namespace App\Dictionary\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dictionary\ApiResource\Entry;
use App\Dictionary\ApiResource\Kanji;
use App\Dictionary\ApiResource\Reading;
use App\Dictionary\ApiResource\Sense;
use App\Dictionary\ApiResource\Translation;
use Meilisearch\Client;
use Override;
use Symfony\Component\Uid\Uuid;
use function Functional\map;

/**
 * @implements ProviderInterface<Entry>
 */
final readonly class SearchProvider implements ProviderInterface
{
    public function __construct(
        private Client $searchClient,
    ) {
    }

    /**
     * @return Entry[]
     */
    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $searchResult = $this->searchClient->index('dictionary')->rawSearch($uriVariables['query']);

        return map($searchResult['hits'], static fn (array $data): Entry => new Entry(
            id: Uuid::fromString($data['entry']['id']),
            kanji: map($data['entry']['kanji'], static fn (array $kanji): Kanji => new Kanji(
                $kanji['value'],
                $kanji['info'],
            )),
            readings: map($data['entry']['readings'], static fn (array $reading): Reading => new Reading(
                kana: $reading['kana'],
                romaji: $reading['romaji'],
                info: $reading['info'],
            )),
            senses: map($data['entry']['senses'], static fn (array $sense): Sense => new Sense(
                partsOfSpeech: $sense['partsOfSpeech'],
                fieldOfApplication: $sense['fieldOfApplication'],
                dialect: $sense['dialect'],
                misc: $sense['misc'],
                info: $sense['info'],
                translations: map($sense['translations'], static fn (array $translation): Translation => new Translation(
                    value: $translation['value'],
                    language: $translation['language'],
                )),
            )),
        ));
    }
}
