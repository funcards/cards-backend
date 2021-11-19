<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandHandler;

final class RemoveCardCommandHandler implements CommandHandler
{
    /**
     * @param RemoveCardCommand $command
     */
    public function __invoke(RemoveCardCommand $command): void
    {}
}
