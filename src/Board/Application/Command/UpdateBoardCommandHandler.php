<?php

declare(strict_types=1);

namespace FC\Board\Application\Command;

use FC\Board\Domain\BoardColor;
use FC\Board\Domain\BoardDescription;
use FC\Board\Domain\BoardName;
use FC\Board\Domain\BoardRepository;
use FC\Board\Domain\Exception\AccessDeniedException;
use FC\Shared\Application\Command\CommandHandler;
use FC\Shared\Domain\Event\EventBus;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\Role;
use FC\Shared\Domain\ValueObject\User\UserId;

final class UpdateBoardCommandHandler implements CommandHandler
{
    /**
     * @param BoardRepository $boardRepository
     * @param EventBus $eventBus
     */
    public function __construct(private BoardRepository $boardRepository, private EventBus $eventBus)
    {
    }

    /**
     * @param UpdateBoardCommand $command
     */
    public function __invoke(UpdateBoardCommand $command): void
    {
        $board = $this->boardRepository->get(BoardId::fromString($command->getBoardId()));

        if (false === $board->isGranted(UserId::fromString($command->getUserId()), Role::boardEdit())) {
            throw AccessDeniedException::new();
        }

        if (null !== $command->getName()) {
            $board->changeName(BoardName::fromString($command->getName()));
        }

        if (null !== $command->getColor()) {
            $board->changeColor(BoardColor::fromString($command->getColor()));
        }

        if (null !== $command->getDescription()) {
            $board->changeDescription(BoardDescription::fromString($command->getDescription()));
        }

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
