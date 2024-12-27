<?php

namespace App;

use App\Event\UserCreatedEvent;
use App\Event\UserUpdatedEvent;
use App\Event\UserDeletedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class UserEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger){}

    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::NAME => 'onUserCreated',
            UserUpdatedEvent::NAME => 'onUserUpdated',
            UserDeletedEvent::NAME => 'onUserDeleted',
        ];
    }

    public function onUserCreated(UserCreatedEvent $event): void
    {
        $user = $event->getUser();
        $this->logger->info(sprintf('User was created: %s', $user->getLogin()));
    }

    public function onUserUpdated(UserUpdatedEvent $event): void
    {
        $user = $event->getUser();
        $this->logger->info(sprintf('User was updated: %s', $user->getLogin()));
    }

    public function onUserDeleted(UserDeletedEvent $event): void
    {
        $user = $event->getUser();
        $this->logger->info(sprintf('User was deleted: %s', $user->getLogin()));
    }
}
