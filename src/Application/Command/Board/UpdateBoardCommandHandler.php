<?php

declare(strict_types=1);

namespace FC\Application\Command\Board;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardColor;
use FC\Domain\Aggregate\BoardAggregate\BoardDescription;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\BoardAggregate\BoardName;
use FC\Domain\Aggregate\BoardAggregate\BoardRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class UpdateBoardCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly BoardRepository $boardRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EventBus $eventBus,
    ) {
    }

    public function __invoke(UpdateBoardCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::BoardEdit)) {
            throw AccessDeniedException::new();
        }

        $board = $this->boardRepository->get($boardId);

        if (null !== $command->name) {
            $board->changeName(BoardName::fromString($command->name));
        }

        if (null !== $command->color) {
            $board->changeColor(BoardColor::fromString($command->color));
        }

        if (null !== $command->description) {
            $board->changeDescription(BoardDescription::fromString($command->description));
        }

        $this->boardRepository->save($board);
        $this->eventBus->publish(...$board->releaseEvents());
    }
}
