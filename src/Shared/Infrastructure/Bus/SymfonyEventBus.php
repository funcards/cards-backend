<?php

declare(strict_types=1);

namespace FC\Shared\Infrastructure\Bus;

use FC\Shared\Domain\Event\DomainEvent;
use FC\Shared\Domain\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class SymfonyEventBus implements EventBus
{
    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->messageBus->dispatch($event, [new DispatchAfterCurrentBusStamp()]);
        }
    }
}
