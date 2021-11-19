<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\CommandHandler;

final class UpdateCardCommandHandler implements CommandHandler
{
    /**
     * @param UpdateCardCommand $command
     */
    public function __invoke(UpdateCardCommand $command): void
    {}
}
