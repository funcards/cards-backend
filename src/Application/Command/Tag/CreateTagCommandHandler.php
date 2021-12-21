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
        private TagRepository $tagRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    public function __invoke(CreateTagCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::tagAdd())) {
            throw AccessDeniedException::new();
        }

        $tag = Tag::create(
            TagId::fromString($command->getTagId()),
            $boardId,
            TagName::fromString($command->getName()),
            TagColor::fromString($command->getColor()),
        );

        $this->tagRepository->save($tag);
        $this->eventBus->publish(...$tag->releaseEvents());
    }
}
