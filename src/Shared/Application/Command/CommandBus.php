<?php

declare(strict_types=1);

namespace FC\Shared\Application\Command;

use FC\Shared\Application\Command\Exception\CommandBusException;

interface CommandBus
{
    /**
     * @param Command $command
     * @return mixed
     * @throws CommandBusException
     * @throws \Throwable
     */
    public function send(Command $command): mixed;
}
