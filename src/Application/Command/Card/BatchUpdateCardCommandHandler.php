<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandBus;
use FC\Application\Bus\Command\CommandHandler;

final class BatchUpdateCardCommandHandler implements CommandHandler
{
    /**
     * @param CommandBus $commandBus
     */
    public function __construct(private CommandBus $commandBus)
    {
    }

    /**
     * @param BatchUpdateCardCommand $batchCommand
     * @return void
     * @throws \Throwable
     */
    public function __invoke(BatchUpdateCardCommand $batchCommand): void
    {
        foreach ($batchCommand->getCommands() as $command) {
            $this->commandBus->send($command);
        }
    }
}
