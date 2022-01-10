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
    public function __construct(private readonly BoardRepository $boardRepository, private readonly EventBus $eventBus)
    {
    }

    public function __invoke(CreateBoardCommand $command): void
    {
        $board = Board::create(
            BoardId::fromString($command->boardId),
            UserId::fromString($command->ownerId),
            BoardName::fromString($command->name),
            BoardColor::fromString($command->color),
            BoardDescription::fromString($command->description),
        );

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
