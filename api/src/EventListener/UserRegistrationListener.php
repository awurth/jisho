<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Dictionary;
use App\Event\UserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final readonly class UserRegistrationListener
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[AsEventListener]
    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $dictionary = new Dictionary();
        $dictionary->name = 'Japonais';
        $dictionary->owner = $event->getUser();

        $this->entityManager->persist($dictionary);
    }
}
