<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Dictionary;
use App\Repository\DictionaryRepository;
use Override;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Dictionary>
 *
 * @method        Dictionary|Proxy                     create(array|callable $attributes = [])
 * @method static Dictionary|Proxy                     createOne(array $attributes = [])
 * @method static Dictionary|Proxy                     find(object|array|mixed $criteria)
 * @method static Dictionary|Proxy                     findOrCreate(array $attributes)
 * @method static Dictionary|Proxy                     first(string $sortedField = 'id')
 * @method static Dictionary|Proxy                     last(string $sortedField = 'id')
 * @method static Dictionary|Proxy                     random(array $attributes = [])
 * @method static Dictionary|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DictionaryRepository|RepositoryProxy repository()
 * @method static Dictionary[]|Proxy[]                 all()
 * @method static Dictionary[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Dictionary[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Dictionary[]|Proxy[]                 findBy(array $attributes)
 * @method static Dictionary[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Dictionary[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Dictionary> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Dictionary> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Dictionary> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Dictionary> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Dictionary> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Dictionary> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Dictionary> random(array $attributes = [])
 * @phpstan-method static Proxy<Dictionary> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Dictionary> repository()
 * @phpstan-method static list<Proxy<Dictionary>> all()
 * @phpstan-method static list<Proxy<Dictionary>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Dictionary>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Dictionary>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Dictionary>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Dictionary>> randomSet(int $number, array $attributes = [])
 */
final class DictionaryFactory extends ModelFactory
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
            // ->afterInstantiate(function(Dictionary $dictionary): void {})
        ;
    }

    #[Override]
    protected static function getClass(): string
    {
        return Dictionary::class;
    }
}
