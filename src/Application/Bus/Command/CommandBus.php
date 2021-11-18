<?php

declare(strict_types=1);

namespace FC\Application\Bus\Command;

use FC\Application\Bus\Command\Exception\CommandBusException;

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
