<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Deck;
use App\Repository\DeckRepository;
use Override;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Deck>
 *
 * @method        Deck|Proxy                     create(array|callable $attributes = [])
 * @method static Deck|Proxy                     createOne(array $attributes = [])
 * @method static Deck|Proxy                     find(object|array|mixed $criteria)
 * @method static Deck|Proxy                     findOrCreate(array $attributes)
 * @method static Deck|Proxy                     first(string $sortedField = 'id')
 * @method static Deck|Proxy                     last(string $sortedField = 'id')
 * @method static Deck|Proxy                     random(array $attributes = [])
 * @method static Deck|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DeckRepository|RepositoryProxy repository()
 * @method static Deck[]|Proxy[]                 all()
 * @method static Deck[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Deck[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Deck[]|Proxy[]                 findBy(array $attributes)
 * @method static Deck[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Deck[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Deck> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Deck> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Deck> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Deck> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Deck> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Deck> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Deck> random(array $attributes = [])
 * @phpstan-method static Proxy<Deck> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Deck> repository()
 * @phpstan-method static list<Proxy<Deck>> all()
 * @phpstan-method static list<Proxy<Deck>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Deck>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Deck>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Deck>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Deck>> randomSet(int $number, array $attributes = [])
 */
final class DeckFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    #[Override]
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(255),
            'owner' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[Override]
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Deck $deck): void {})
        ;
    }

    #[Override]
    protected static function getClass(): string
    {
        return Deck::class;
    }
}
