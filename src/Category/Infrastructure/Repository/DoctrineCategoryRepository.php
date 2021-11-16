<?php

declare(strict_types=1);

namespace FC\Category\Infrastructure\Repository;

use FC\Category\Domain\Category;
use FC\Category\Domain\CategoryRepository;
use FC\Shared\Domain\ValueObject\Category\CategoryId;
use FC\Shared\Infrastructure\Repository\DoctrineRepository;

final class DoctrineCategoryRepository extends DoctrineRepository implements CategoryRepository
{
    /**
     * {@inheritDoc}
     */
    public function get(CategoryId $id): Category
    {
        return $this->find(Category::class, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(Category $category): void
    {
        $this->persist($category);
    }
}
