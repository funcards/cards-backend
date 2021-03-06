<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandBus;
use FC\Application\Bus\Command\CommandHandler;

final class BatchUpdateCardCommandHandler implements CommandHandler
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(BatchUpdateCardCommand $batchCommand): void
    {
        foreach ($batchCommand->commands as $command) {
            $this->commandBus->send($command);
        }
    }
}
