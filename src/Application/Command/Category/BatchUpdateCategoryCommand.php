<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\Command;

final class BatchUpdateCategoryCommand implements Command
{
    /**
     * @var UpdateCategoryCommand[]
     */
    public readonly array $commands;

    public function __construct(UpdateCategoryCommand ...$commands)
    {
        $this->commands = $commands;
    }
}
