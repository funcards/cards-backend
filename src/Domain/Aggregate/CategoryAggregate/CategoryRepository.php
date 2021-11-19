<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CategoryAggregate;

use FC\Domain\Exception\NotFoundException;

interface CategoryRepository
{
    /**
     * @param CategoryId $id
     * @return Category
     * @throws NotFoundException
     */
    public function get(CategoryId $id): Category;

    /**
     * @param Category $category
     */
    public function save(Category $category): void;

    /**
     * @param Category $category
     */
    public function remove(Category $category): void;
}
