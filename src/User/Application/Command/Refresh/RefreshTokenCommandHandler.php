<?php

namespace FC\User\Application\Command\Refresh;

use FC\Shared\Application\Command\CommandHandler;
use FC\User\Application\Auth\AuthSession;
use FC\User\Application\Auth\AuthSessionServiceInterface;

final class RefreshTokenCommandHandler implements CommandHandler
{
    /**
     * @param AuthSessionServiceInterface $authSessionService
     */
    public function __construct(private AuthSessionServiceInterface $authSessionService)
    {
    }

    /**
     * @param RefreshTokenCommand $command
     * @return AuthSession
     * @throws \Throwable
     */
    public function __invoke(RefreshTokenCommand $command): AuthSession
    {
        return $this->authSessionService->refreshSession($command->getToken());
    }
}
