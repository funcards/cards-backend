<?php

declare(strict_types=1);

namespace FC\Infrastructure\Bus;

use FC\Application\Bus\Query\Exception\QueryHandlerNotFoundException;
use FC\Application\Bus\Query\Query;
use FC\Application\Bus\Query\QueryBus;
use FC\Application\Bus\Query\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

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
