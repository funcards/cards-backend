<?php

declare(strict_types=1);

namespace FC\Application\Command\Auth;

use FC\Application\Auth\Tokens;
use FC\Application\Auth\AuthSessionServiceInterface;
use FC\Application\Bus\Command\CommandHandler;

final class RefreshTokenCommandHandler implements CommandHandler
{
    public function __construct(private readonly AuthSessionServiceInterface $authSessionService)
    {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(RefreshTokenCommand $command): Tokens
    {
        return $this->authSessionService->refreshSession($command->token);
    }
}
