<?php

declare(strict_types=1);

namespace FC\Application\Command\Tag;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\TagAggregate\TagColor;
use FC\Domain\Aggregate\TagAggregate\TagId;
use FC\Domain\Aggregate\TagAggregate\TagName;
use FC\Domain\Aggregate\TagAggregate\TagRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class UpdateTagCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EventBus $eventBus,
    ) {
    }

    public function __invoke(UpdateTagCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::TagEdit)) {
            throw AccessDeniedException::new();
        }

        $tag = $this->tagRepository->get(TagId::fromString($command->tagId));

        if (null !== $command->name) {
            $tag->changeName(TagName::fromString($command->name));
        }

        if (null !== $command->color) {
            $tag->changeColor(TagColor::fromString($command->color));
        }

        $this->tagRepository->save($tag);
        $this->eventBus->publish(...$tag->releaseEvents());
    }
}
