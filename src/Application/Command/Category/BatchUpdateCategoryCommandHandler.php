<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\CommandBus;
use FC\Application\Bus\Command\CommandHandler;

final class BatchUpdateCategoryCommandHandler implements CommandHandler
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(BatchUpdateCategoryCommand $batchCommand): void
    {
        foreach ($batchCommand->getCommands() as $command) {
            $this->commandBus->send($command);
        }
    }
}
