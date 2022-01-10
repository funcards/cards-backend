<?php

declare(strict_types=1);

namespace FC\Application\Command\Card;

use FC\Application\Bus\Command\Command;

final class BatchUpdateCardCommand implements Command
{
    /**
     * @var UpdateCardCommand[]
     */
    public readonly array $commands;

    public function __construct(UpdateCardCommand ...$commands)
    {
        $this->commands = $commands;
    }
}
