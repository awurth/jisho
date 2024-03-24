<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Dictionary;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist)]
final readonly class UserRegistrationListener
{
    public function prePersist(PrePersistEventArgs $event): void
    {
        $entity = $event->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $dictionary = new Dictionary();
        $dictionary->setName('Japonais');
        $dictionary->setOwner($entity);

        $event->getObjectManager()->persist($dictionary);
    }
}
