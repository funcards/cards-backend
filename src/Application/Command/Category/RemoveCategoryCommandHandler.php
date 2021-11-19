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
    /**
     * @param CategoryRepository $categoryRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        private CategoryRepository $categoryRepository,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    /**
     * @param RemoveCategoryCommand $command
     */
    public function __invoke(RemoveCategoryCommand $command): void
    {
        $boardId = BoardId::fromString($command->getBoardId());
        $userId = UserId::fromString($command->getUserId());

        if (false === $this->authorizationChecker->isGranted($boardId, $userId, Role::categoryRemove())) {
            throw AccessDeniedException::new();
        }

        $category = $this->categoryRepository->get(CategoryId::fromString($command->getCategoryId()));

        $this->categoryRepository->remove($category);
    }
}
