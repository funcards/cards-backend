<?php

declare(strict_types=1);

namespace FC\Infrastructure\Bus;

use FC\Application\Bus\Event\EventBus;
use FC\Domain\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class SymfonyEventBus implements EventBus
{
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
