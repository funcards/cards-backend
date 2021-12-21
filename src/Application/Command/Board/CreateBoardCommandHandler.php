<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\Board;
use FC\Domain\Aggregate\BoardAggregate\BoardColor;
use FC\Domain\Aggregate\BoardAggregate\BoardDescription;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\BoardAggregate\BoardName;
use FC\Domain\Aggregate\BoardAggregate\BoardRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;

final class CreateBoardCommandHandler implements CommandHandler
{
    public function __construct(private BoardRepository $boardRepository, private EventBus $eventBus)
    {
    }

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
