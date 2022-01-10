<?php

declare(strict_types=1);

namespace FC\Application\Command\Tag;

use FC\Application\Bus\Command\CommandHandler;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\TagAggregate\TagId;
use FC\Domain\Aggregate\TagAggregate\TagRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class RemoveTagCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function __invoke(RemoveTagCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::TagRemove)) {
            throw AccessDeniedException::new();
        }

        $tag = $this->tagRepository->get(TagId::fromString($command->tagId));

        $this->tagRepository->remove($tag);
    }
}
