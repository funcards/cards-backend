<?php

declare(strict_types=1);

namespace FC\Board\Application\Command;

use FC\Board\Domain\Board;
use FC\Board\Domain\BoardColor;
use FC\Board\Domain\BoardDescription;
use FC\Board\Domain\BoardName;
use FC\Board\Domain\BoardRepository;
use FC\Shared\Application\Command\CommandHandler;
use FC\Shared\Domain\Event\EventBus;
use FC\Shared\Domain\ValueObject\Board\BoardId;
use FC\Shared\Domain\ValueObject\User\UserId;

final class CreateBoardCommandHandler implements CommandHandler
{
    /**
     * @param BoardRepository $boardRepository
     * @param EventBus $eventBus
     */
    public function __construct(private BoardRepository $boardRepository, private EventBus $eventBus)
    {
    }

    /**
     * @param CreateBoardCommand $command
     */
    public function __invoke(CreateBoardCommand $command): void
    {
        $board = Board::create(
            BoardId::fromString($command->getBoardId()),
            UserId::fromString($command->getOwnerId()),
            BoardName::fromString($command->getName()),
            BoardColor::fromString($command->getColor()),
            BoardDescription::fromString($command->getDescription()),
        );

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
