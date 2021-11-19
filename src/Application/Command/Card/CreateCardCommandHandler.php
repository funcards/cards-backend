<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandHandler;

final class CreateCardCommandHandler implements CommandHandler
{
    /**
     * @param CreateCardCommand $command
     */
    public function __invoke(CreateCardCommand $command): void
    {}
}
