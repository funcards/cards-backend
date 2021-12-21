<?php

declare(strict_types=1);

namespace FC\Application\Bus\Command\Exception;

use FC\Application\Bus\Command\Command;
use JetBrains\PhpStorm\Pure;

final class CommandHandlerNotFoundException extends \RuntimeException implements CommandBusException
{
    #[Pure]
    public static function create(Command $command, ?\Throwable $previous = null): self
    {
        return new self(\sprintf('Handler for command "%s" not found.', $command::class), 0, $previous);
    }
}
