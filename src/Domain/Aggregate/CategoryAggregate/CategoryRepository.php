<?php

declare(strict_types=1);

namespace FC\Domain\Aggregate\CategoryAggregate;

use FC\Domain\Exception\NotFoundException;

interface CategoryRepository
{
    /**
     * @throws NotFoundException
     */
    public function get(CategoryId $id): Category;

    public function save(Category $category): void;

    public function remove(Category $category): void;
}
