<?php

declare(strict_types=1);

namespace FC\Application\Bus\Command;

use FC\Application\Bus\Command\Exception\CommandBusException;

interface CommandBus
{
    /**
     * @throws CommandBusException
     * @throws \Throwable
     */
    public function send(Command $command): mixed;
}
