<?php

declare(strict_types=1);

namespace App\Common\Entity\Dictionary;

use App\Common\Repository\Dictionary\EntryRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: EntryRepository::class)]
#[Table(name: 'dictionary_entry')]
class Entry
{
    #[Id]
    #[Column(type: 'uuid')]
    public Uuid $id;

    #[Column(unique: true)]
    public int $sequenceId;

    /**
     * @var KanjiElement[]
     */
    #[Column(type: 'json_document', options: ['jsonb' => true])]
    public array $kanjiElements;

    /**
     * @var ReadingElement[]
     */
    #[Column(type: 'json_document', options: ['jsonb' => true])]
    public array $readingElements;

    /**
     * @var Sense[]
     */
    #[Column(type: 'json_document', options: ['jsonb' => true])]
    public array $senses;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }
}
