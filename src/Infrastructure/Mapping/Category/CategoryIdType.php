<?php

declare(strict_types=1);

namespace FC\Infrastructure\Mapping\Category;

use FC\Domain\Aggregate\CategoryAggregate\CategoryId;
use FC\Infrastructure\Mapping\IdType;

final class CategoryIdType extends IdType
{
    /**
     * {@inheritDoc}
     */
    protected function getIdClass(): string
    {
        return CategoryId::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'category_id';
    }
}
