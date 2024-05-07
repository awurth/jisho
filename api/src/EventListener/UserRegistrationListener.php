<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Deck\Deck;
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
        $deck = new Deck();
        $deck->name = 'Principal';
        $deck->owner = $event->getUser();

        $this->entityManager->persist($deck);
    }
}
