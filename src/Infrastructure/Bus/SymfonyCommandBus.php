<?php

declare(strict_types=1);

namespace FC\Infrastructure\Bus;

use FC\Application\Bus\Command\Command;
use FC\Application\Bus\Command\CommandBus;
use FC\Application\Bus\Command\Exception\CommandHandlerNotFoundException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyCommandBus implements CommandBus
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
    public function send(Command $command): mixed
    {
        try {
            /** @var HandledStamp|null $stamp */
            $stamp = $this->messageBus->dispatch($command)->last(HandledStamp::class);

            return $stamp?->getResult();
        } catch (NoHandlerForMessageException $e) {
            throw CommandHandlerNotFoundException::create($command, $e);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
