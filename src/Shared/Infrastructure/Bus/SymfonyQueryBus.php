<?php

declare(strict_types=1);

namespace FC\Shared\Infrastructure\Bus;

use FC\Shared\Application\Query\Exception\QueryHandlerNotFoundException;
use FC\Shared\Application\Query\Query;
use FC\Shared\Application\Query\QueryBus;
use FC\Shared\Application\Query\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * {@inheritDoc}
     */
    public function ask(Query $query): Response
    {
        try {
            return $this->handle($query);
        } catch (NoHandlerForMessageException $e) {
            throw QueryHandlerNotFoundException::create($query, $e);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
