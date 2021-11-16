<?php

declare(strict_types=1);

namespace FC\Category\Domain;

use FC\Shared\Domain\Exception\NotFoundDomainException;
use FC\Shared\Domain\ValueObject\Category\CategoryId;

interface CategoryRepository
{
    /**
     * @param CategoryId $id
     * @return Category
     * @throws NotFoundDomainException
     */
    public function get(CategoryId $id): Category;

    /**
     * @param Category $category
     */
    public function save(Category $category): void;
}
