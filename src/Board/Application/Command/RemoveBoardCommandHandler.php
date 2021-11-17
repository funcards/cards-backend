<?php

declare(strict_types=1);

namespace FC\Board\Application\Command;

use FC\Board\Domain\BoardRepository;
use FC\Board\Domain\Exception\AccessDeniedException;
use FC\Shared\Application\Command\CommandHandler;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\Role;
use FC\Shared\Domain\ValueObject\User\UserId;

final class RemoveBoardCommandHandler implements CommandHandler
{
    /**
     * @param BoardRepository $boardRepository
     */
    public function __construct(private BoardRepository $boardRepository)
    {
    }

    /**
     * @param RemoveBoardCommand $command
     */
    public function __invoke(RemoveBoardCommand $command): void
    {
        $board = $this->boardRepository->get(BoardId::fromString($command->getBoardId()));

        if (false === $board->isGranted(UserId::fromString($command->getUserId()), Role::boardRemove())) {
            throw AccessDeniedException::new();
        }

        $this->boardRepository->remove($board);
    }
}
