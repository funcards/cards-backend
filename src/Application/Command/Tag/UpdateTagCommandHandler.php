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
    /**
     * @param TagRepository $tagRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param EventBus $eventBus
     */
    public function __construct(
        private TagRepository $tagRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
        private EventBus $eventBus,
    ) {
    }

    /**
     * @param UpdateTagCommand $command
     */
    public function __invoke(UpdateTagCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::tagEdit())) {
            throw AccessDeniedException::new();
        }

        $tag = $this->tagRepository->get(TagId::fromString($command->getTagId()));

        if (null !== $command->getName()) {
            $tag->changeName(TagName::fromString($command->getName()));
        }

        if (null !== $command->getColor()) {
            $tag->changeColor(TagColor::fromString($command->getColor()));
        }

        $this->tagRepository->save($tag);
        $this->eventBus->publish(...$tag->releaseEvents());
    }
}