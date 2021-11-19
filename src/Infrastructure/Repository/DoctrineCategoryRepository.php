<?php

declare(strict_types=1);

namespace FC\Infrastructure\Repository;

use FC\Domain\Aggregate\CategoryAggregate\Category;
use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Domain\Aggregate\CategoryAggregate\CategoryRepository;

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

    /**
     * {@inheritDoc}
     */
    public function remove(Category $category): void
    {
        $this->delete($category);
    }
}
