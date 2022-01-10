<?php

declare(strict_types=1);

namespace FC\Application\Command\Tag;

use FC\Application\Bus\Command\CommandHandler;
use FC\Application\Bus\Event\EventBus;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\TagAggregate\Tag;
use FC\Domain\Aggregate\TagAggregate\TagColor;
use FC\Domain\Aggregate\TagAggregate\TagId;
use FC\Domain\Aggregate\TagAggregate\TagName;
use FC\Domain\Aggregate\TagAggregate\TagRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class CreateTagCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EventBus $eventBus,
    ) {
    }

    public function __invoke(CreateTagCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::TagAdd)) {
            throw AccessDeniedException::new();
        }

        $tag = Tag::create(
            TagId::fromString($command->tagId),
            $boardId,
            TagName::fromString($command->name),
            TagColor::fromString($command->color),
        );

        $this->tagRepository->save($tag);
        $this->eventBus->publish(...$tag->releaseEvents());
    }
}
