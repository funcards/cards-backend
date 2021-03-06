<?php

declare(strict_types=1);

namespace FC\Application\Command\Category;

use FC\Application\Bus\Command\CommandHandler;
use FC\Domain\Aggregate\BoardAggregate\BoardId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryRepository;
use FC\Domain\Aggregate\UserAggregate\UserId;
use FC\Domain\Authorization\AuthorizationCheckerInterface;
use FC\Domain\Exception\AccessDeniedException;
use FC\Domain\ValueObject\Role;

final class RemoveCategoryCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function __invoke(RemoveCategoryCommand $command): void
    {
        $boardId = BoardId::fromString($command->boardId);
        $userId = UserId::fromString($command->userId);

        if (!$this->authorizationChecker->isGranted($boardId, $userId, Role::CategoryRemove)) {
            throw AccessDeniedException::new();
        }

        $category = $this->categoryRepository->get(CategoryId::fromString($command->categoryId));

        $this->categoryRepository->remove($category);
    }
}
